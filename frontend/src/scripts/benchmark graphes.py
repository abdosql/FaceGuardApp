import matplotlib.pyplot as plt
import matplotlib.dates as mdates
from datetime import datetime, timedelta
import matplotlib.patches as mpatches

# Define the new start dates and durations
tasks = [
    {"name": "Sprint 1: Préparation", "start": "2023-12-25", "duration": 7},
    {"name": "Sprint 2: Analyse et Conception", "start": "2024-01-01", "duration": 30},
    {"name": "Sprint 3: Dev App Web", "start": "2024-01-31", "duration": 60},
    {"name": "Sprint 4: Dev et Intégration API", "start": "2024-04-01", "duration": 30},
    {"name": "Sprint 5: Système RFID", "start": "2024-05-01", "duration": 15},
    {"name": "Sprint 6: Statistiques et Paramètres", "start": "2024-05-16", "duration": 12},
    {"name": "Sprint 7: Tests et Déploiement", "start": "2024-05-28", "duration": 1}
]

# Convert start dates and durations to appropriate format
for task in tasks:
    task["start"] = datetime.strptime(task["start"], "%Y-%m-%d")
    task["end"] = task["start"] + timedelta(days=task["duration"])

# Define colors for the bars
colors = ['#ff9999','#66b3ff','#99ff99','#ffcc99','#c2c2f0','#ffb3e6','#c2f0c2']

# Create the plot
fig, ax = plt.subplots(figsize=(18, 8))  # Increased width

# Add tasks to the Gantt chart
for i, task in enumerate(tasks):
    ax.barh(task["name"], task["duration"], left=task["start"], color=colors[i % len(colors)], edgecolor='black')

# Format the x-axis as dates
ax.xaxis.set_major_locator(mdates.WeekdayLocator(interval=1))
ax.xaxis.set_major_formatter(mdates.DateFormatter("%Y-%m-%d"))

# Adding grid lines for better readability
ax.grid(True, which='both', linestyle='--', linewidth=0.5)

# Set labels
ax.set_xlabel('Date', fontsize=14)
ax.set_ylabel('Tasks', fontsize=14)
ax.set_title('Gantt Chart', fontsize=16)

# Customize tick parameters
ax.tick_params(axis='x', which='major', labelsize=10, rotation=45)
ax.tick_params(axis='y', which='major', labelsize=12)

# Create a legend
patches = [mpatches.Patch(color=colors[i % len(colors)], label=task["name"]) for i, task in enumerate(tasks)]
plt.legend(handles=patches, bbox_to_anchor=(1.05, 1), loc='upper left', borderaxespad=0.)

# Display the plot
plt.tight_layout()
plt.savefig('gantt_chart.png')
plt.show()
