import json
import matplotlib.pyplot as plt
import os

def plot_benchmark_data(json_file, output_file):
    # Load the benchmark data from the JSON file
    with open(json_file, 'r') as file:
        data = json.load(file)

    # Extract the data for each configuration
    config_names = list(data.keys())
    elapsed_times = [data[config][0]['elapsed_time'] for config in config_names]
    memory_usages = [data[config][0]['memory_usage'] for config in config_names]
    num_groups = [data[config][0]['num_groups'] for config in config_names]
    num_teachers = [data[config][0]['num_teachers'] for config in config_names]
    num_courses = [data[config][0]['num_courses'] for config in config_names]
    slots_per_day = [data[config][0]['slots_per_day'] for config in config_names]

    # Create the figure and axes
    fig, ax = plt.subplots(2, 3, figsize=(12, 8))

    # Plot the elapsed time
    ax[0, 0].bar(config_names, elapsed_times)
    ax[0, 0].set_title('Elapsed Time')
    ax[0, 0].set_xlabel('Configuration')
    ax[0, 0].set_ylabel('Time (s)')

    # Plot the memory usage
    ax[0, 1].bar(config_names, memory_usages)
    ax[0, 1].set_title('Memory Usage')
    ax[0, 1].set_xlabel('Configuration')
    ax[0, 1].set_ylabel('Memory (MB)')

    # Plot the number of groups
    ax[0, 2].bar(config_names, num_groups)
    ax[0, 2].set_title('Number of Groups')
    ax[0, 2].set_xlabel('Configuration')
    ax[0, 2].set_ylabel('Number of Groups')

    # Plot the number of teachers
    ax[1, 0].bar(config_names, num_teachers)
    ax[1, 0].set_title('Number of Teachers')
    ax[1, 0].set_xlabel('Configuration')
    ax[1, 0].set_ylabel('Number of Teachers')

    # Plot the number of courses
    ax[1, 1].bar(config_names, num_courses)
    ax[1, 1].set_title('Number of Courses')
    ax[1, 1].set_xlabel('Configuration')
    ax[1, 1].set_ylabel('Number of Courses')

    # Plot the number of slots per day
    ax[1, 2].bar(config_names, slots_per_day)
    ax[1, 2].set_title('Slots per Day')
    ax[1, 2].set_xlabel('Configuration')
    ax[1, 2].set_ylabel('Slots per Day')

    # Add annotations to the chart
    for i, config in enumerate(config_names):
        ax[0, 0].text(i, elapsed_times[i] + 0.1, f"{elapsed_times[i]:.2f}", ha='center', va='bottom')
        ax[0, 1].text(i, memory_usages[i] + 0.1, f"{memory_usages[i]:.2f}", ha='center', va='bottom')
        ax[0, 2].text(i, num_groups[i] + 0.1, str(num_groups[i]), ha='center', va='bottom')
        ax[1, 0].text(i, num_teachers[i] + 0.1, str(num_teachers[i]), ha='center', va='bottom')
        ax[1, 1].text(i, num_courses[i] + 0.1, str(num_courses[i]), ha='center', va='bottom')
        ax[1, 2].text(i, slots_per_day[i] + 0.1, str(slots_per_day[i]), ha='center', va='bottom')

    # Adjust the spacing between subplots
    plt.subplots_adjust(wspace=0.5, hspace=0.5)

    # Save the plot to a file
    plt.savefig(output_file)

    # Show the plot
    plt.show()

# Example usage
json_file = 'benchmark_results.json'
output_file = 'benchmark_plot.png'
if os.path.isfile(json_file):
    plot_benchmark_data(json_file, output_file)
else:
    print(f"Error: File '{json_file}' not found.")

plot_benchmark_data('benchmark_results.json', 'benchmark_plot.png')