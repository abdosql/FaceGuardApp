from flask import Flask, request, jsonify
from ortools.sat.python import cp_model
import concurrent.futures

app = Flask(__name__)

def create_and_solve_schedule(groups, teachers, classrooms, course_sessions, num_days=6, num_slots=4):
    model = cp_model.CpModel()

    # Variables
    x = {}
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

    # Constraints
    for year in groups:
        for department in groups[year]:
            for group in groups[year][department]:
                for course in groups[year][department][group]:
                    model.Add(sum(x[year][department][group][day][slot][course][teacher][classroom]
                                  for day in range(num_days)
                                  for slot in range(num_slots)
                                  for teacher in teachers if course in teachers[teacher]
                                  for classroom in range(classrooms)) == course_sessions.get(course, 2))

    for teacher in teachers:
        for day in range(num_days):
            for slot in range(num_slots):
                model.Add(sum(x[year][department][group][day][slot][course][teacher][classroom]
                              for year in groups
                              for department in groups[year]
                              for group in groups[year][department]
                              for course in groups[year][department][group] if course in teachers[teacher]
                              for classroom in range(classrooms)) <= 1)

    for classroom in range(classrooms):
        for day in range(num_days):
            for slot in range(num_slots):
                model.Add(sum(x[year][department][group][day][slot][course][teacher][classroom]
                              for year in groups
                              for department in groups[year]
                              for group in groups[year][department]
                              for course in groups[year][department][group]
                              for teacher in teachers if course in teachers[teacher]) <= 1)

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
    status = solver.Solve(model)

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
        return solution
    else:
        return {"error": "No solution found."}

def parallel_scheduling(data):
    groups = data.get('groups')
    teachers = data.get('teachers')
    classrooms = data.get('classrooms')
    course_sessions = data.get('course_sessions')
    days = data.get('days')
    slots_per_day = data.get('slots_per_day')
    return create_and_solve_schedule(groups, teachers, classrooms, course_sessions, days, slots_per_day)

@app.route('/schedule', methods=['POST'])
def schedule():
    data = request.json

    # Use a ThreadPoolExecutor to run the solver in parallel
    with concurrent.futures.ThreadPoolExecutor() as executor:
        future = executor.submit(parallel_scheduling, data)
        result = future.result()

    return jsonify(result)

if __name__ == '__main__':
    app.run(debug=True)