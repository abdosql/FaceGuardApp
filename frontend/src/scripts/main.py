from flask import Flask, request, jsonify
from ortools.sat.python import cp_model

app = Flask(__name__)


def generate_schedule(groups, teachers, classrooms, course_sessions):
    model = cp_model.CpModel()
    x = {}  # x[group][day][slot][course][teacher][classroom]
    for group in groups:
        x[group] = {}
        for day in range(6):
            x[group][day] = {}
            for slot in range(5):
                x[group][day][slot] = {}
                for course in groups[group]:
                    x[group][day][slot][course] = {}
                    for teacher in teachers:
                        x[group][day][slot][course][teacher] = {}
                        for classroom in range(classrooms):
                            var_name = f"x_g{group}_d{day}_s{slot}_c{course}_t{teacher}_r{classroom}"
                            x[group][day][slot][course][teacher][classroom] = model.NewBoolVar(var_name)

    # Constraint: Each course is scheduled for the specified number of sessions per week per group
    for group in groups:
        for course in groups[group]:
            model.Add(sum(x[group][day][slot][course][teacher][classroom]
                          for day in range(5)
                          for slot in range(4)
                          for teacher in teachers if course in teachers[teacher]
                          for classroom in range(classrooms)) == course_sessions.get(course, 2))

    # Constraint: Each teacher teaches at most one course per group per time slot
    for teacher in teachers:
        for day in range(5):
            for slot in range(4):
                model.Add(sum(x[group][day][slot][course][teacher][classroom]
                              for group in groups
                              for course in groups[group] if course in teachers[teacher]
                              for classroom in range(classrooms)) <= 1)

    # Constraint: Each classroom hosts at most one group per time slot
    for classroom in range(classrooms):
        for day in range(5):
            for slot in range(4):
                model.Add(sum(x[group][day][slot][course][teacher][classroom]
                              for group in groups
                              for course in groups[group]
                              for teacher in teachers if course in teachers[teacher]) <= 1)

    # Constraint: Each group attends at most one course per slot
    for group in groups:
        for day in range(5):
            model.Add(sum(x[group][day][slot][course][teacher][classroom]
                          for course in groups[group]
                          for teacher in teachers if course in teachers[teacher]
                          for classroom in range(classrooms)) <= 1)

    # Solve the model
    solver = cp_model.CpSolver()
    solver.parameters.max_time_in_seconds = 60.0
    status = solver.Solve(model)

    if status == cp_model.OPTIMAL or status == cp_model.FEASIBLE:
        solution = {}
        for group in groups:
            solution[group] = {}
            for day in range(5):
                solution[group][day] = {}
                for slot in range(4):
                    solution[group][day][slot] = None
                    for course in groups[group]:
                        for teacher in teachers:
                            for classroom in range(classrooms):
                                if solver.Value(x[group][day][slot][course][teacher][classroom]):
                                    solution[group][day][slot] = (course, teacher, classroom)
        return solution
    else:
        return None


@app.route('/schedule', methods=['POST'])
def schedule():
    data = request.get_json()
    groups = data['groups']
    teachers = data['teachers']
    classrooms = data['classrooms']
    course_sessions = data['course_sessions']

    schedule_solution = generate_schedule(groups, teachers, classrooms, course_sessions)

    if schedule_solution:
        return jsonify(schedule_solution), 200
    else:
        return jsonify({"error": "No feasible schedule could be found"}), 400


if __name__ == "__main__":
    app.run(debug=True)
