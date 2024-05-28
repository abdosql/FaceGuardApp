import json
import time
import psutil
from flask import Flask, request, jsonify
from ortools.sat.python import cp_model

app = Flask(__name__)

def create_and_solve_schedule(groups, teachers, classrooms, course_sessions, num_days=6, num_slots=4):
    model = cp_model.CpModel()

    # Variables
    x = {}  # x[year][department][group][day][slot][course][teacher][classroom]
    for year in groups:
        x[year] = {}
        for department in groups[year]:
            x[year][department] = {}
            for group in groups[year][department]:
                x[year][department][group] = {}
                for day in range(num_days):
                    x[year][department][group][day] = {}
                    for slot in range(num_slots):
                        x[year][department][group][day][slot] = {}
                        for course in groups[year][department][group]:
                            x[year][department][group][day][slot][course] = {}
                            for teacher in teachers:
                                if course in teachers[teacher]:
                                    x[year][department][group][day][slot][course][teacher] = {}
                                    for classroom in range(classrooms):
                                        var_name = f"x_{year}_{department}_{group}_d{day}_s{slot}_c{course}_t{teacher}_r{classroom}"
                                        x[year][department][group][day][slot][course][teacher][classroom] = model.NewBoolVar(var_name)

    # Constraint: Each course is scheduled for the specified number of sessions per week per group
    for year in groups:
        for department in groups[year]:
            for group in groups[year][department]:
                for course in groups[year][department][group]:
                    model.Add(sum(x[year][department][group][day][slot][course][teacher][classroom]
                                  for day in range(num_days)
                                  for slot in range(num_slots)
                                  for teacher in teachers if course in teachers[teacher]
                                  for classroom in range(classrooms)) == course_sessions.get(course, 2))

    # Constraint: Each teacher teaches at most one course per group per time slot
    for teacher in teachers:
        for day in range(num_days):
            for slot in range(num_slots):
                model.Add(sum(x[year][department][group][day][slot][course][teacher][classroom]
                              for year in groups
                              for department in groups[year]
                              for group in groups[year][department]
                              for course in groups[year][department][group] if course in teachers[teacher]
                              for classroom in range(classrooms)) <= 1)

    # Constraint: Each classroom hosts at most one group per time slot
    for classroom in range(classrooms):
        for day in range(num_days):
            for slot in range(num_slots):
                model.Add(sum(x[year][department][group][day][slot][course][teacher][classroom]
                              for year in groups
                              for department in groups[year]
                              for group in groups[year][department]
                              for course in groups[year][department][group]
                              for teacher in teachers if course in teachers[teacher]) <= 1)

    # Constraint: Each group attends at most one course per slot
    for year in groups:
        for department in groups[year]:
            for group in groups[year][department]:
                for day in range(num_days):
                    for slot in range(num_slots):
                        model.Add(sum(x[year][department][group][day][slot][course][teacher][classroom]
                                      for course in groups[year][department][group]
                                      for teacher in teachers if course in teachers[teacher]
                                      for classroom in range(classrooms)) <= 1)

    # Solve the model with a higher time limit
    solver = cp_model.CpSolver()
    solver.parameters.max_time_in_seconds = 2400
    start_time = time.time()
    status = solver.Solve(model)
    end_time = time.time()

    elapsed_time = end_time - start_time
    memory_usage = psutil.Process().memory_info().rss / 1024 / 1024  # Memory usage in MB

    if status == cp_model.OPTIMAL or status == cp_model.FEASIBLE:
        solution = {}
        for year in groups:
            solution[year] = {}
            for department in groups[year]:
                solution[year][department] = {}
                for group in groups[year][department]:
                    solution[year][department][group] = {}
                    for day in range(num_days):
                        solution[year][department][group][day] = {}
                        for slot in range(num_slots):
                            solution[year][department][group][day][slot] = None
                            for course in groups[year][department][group]:
                                for teacher in teachers:
                                    if course in teachers[teacher]:
                                        for classroom in range(classrooms):
                                            if solver.Value(x[year][department][group][day][slot][course][teacher][classroom]):
                                                solution[year][department][group][day][slot] = (course, teacher, classroom)

        return solution, elapsed_time, memory_usage
    else:
        return {"error": "No solution found."}, elapsed_time, memory_usage

def generate_benchmark_data(scenario='simple'):
    if scenario == 'simple':
        return {
            'groups': {
                'Year1': {
                    'Dept1': {
                        'Group1': ['Course1', 'Course2'],
                        'Group2': ['Course3', 'Course4']
                    }
                }
            },
            'teachers': {
                'Teacher1': ['Course1', 'Course3'],
                'Teacher2': ['Course2', 'Course4']
            },
            'classrooms': 2,
            'course_sessions': {
                'Course1': 3,
                'Course2': 2,
                'Course3': 2,
                'Course4': 3
            },
            'days': 5,
            'slots_per_day': 4
        }
    # Add more complex scenarios as needed
    return {}

def save_benchmark_result(scenario, result, elapsed_time, memory_usage, num_groups, num_teachers, num_courses, slots_per_day):
    benchmark_file = 'benchmark_results.json'
    try:
        # Load existing data if file exists
        try:
            with open(benchmark_file, 'r') as f:
                benchmark_data = json.load(f)
            print(f"Loaded existing benchmark data from {benchmark_file}")
        except (FileNotFoundError, json.JSONDecodeError):
            benchmark_data = {}
            print(f"{benchmark_file} not found or contains invalid JSON. Creating new file.")

        # Append new data to the scenario's list
        if scenario not in benchmark_data:
            benchmark_data[scenario] = []

        benchmark_data[scenario].append({
            'elapsed_time': elapsed_time,
            'memory_usage': memory_usage,
            'num_groups': num_groups,
            'num_teachers': num_teachers,
            'num_courses': num_courses,
            'slots_per_day': slots_per_day
        })

        # Write updated data back to file
        with open(benchmark_file, 'w') as f:
            json.dump(benchmark_data, f, indent=4)
        print(f"Saved benchmark result for scenario: {scenario}")

    except Exception as e:
        print(f"Error saving benchmark result: {e}")

@app.route('/schedule', methods=['POST'])
def schedule():
    data = request.json

    if 'benchmark' in data:
        scenario = data.get('benchmark')
        benchmark_data = generate_benchmark_data(scenario)
        result, elapsed_time, memory_usage = create_and_solve_schedule(
            benchmark_data['groups'],
            benchmark_data['teachers'],
            benchmark_data['classrooms'],
            benchmark_data['course_sessions'],
            benchmark_data['days'],
            benchmark_data['slots_per_day']
        )
        # Extract number of groups and teachers from benchmark data
        num_groups = sum(len(departments) for departments in benchmark_data['groups'].values())
        num_teachers = sum(len(teachers) for teachers in benchmark_data['teachers'].values())
        num_courses = len(benchmark_data['course_sessions'])

        save_benchmark_result(scenario, result, elapsed_time, memory_usage, num_groups, num_teachers, num_courses,  benchmark_data['slots_per_day'])
        return jsonify({
            'result': result,
            'elapsed_time': elapsed_time,
            'memory_usage': memory_usage,
            'num_groups': num_groups,
            'num_teachers': num_teachers
        })
    groups = data.get('groups')
    teachers = data.get('teachers')
    classrooms = data.get('classrooms')
    course_sessions = data.get('course_sessions')
    days = data.get('days')
    slots_per_day = data.get('slots_per_day')
    # Extract number of groups and teachers from benchmark data
    num_groups = sum(len(departments) for departments in groups.values())
    num_teachers = sum(len(teachers) for teachers in teachers.values())
    num_courses = len(course_sessions)


    # Call the scheduling function
    result, elapsed_time, memory_usage = create_and_solve_schedule(groups, teachers, classrooms, course_sessions, days, slots_per_day)
    i=0
    save_benchmark_result(i, result, elapsed_time, memory_usage, num_groups, num_teachers, num_courses, slots_per_day)
    i += 1
    return jsonify({
        'result': result,
        'elapsed_time': elapsed_time,
        'memory_usage': memory_usage
    })

if __name__ == '__main__':
    app.run(debug=True)
