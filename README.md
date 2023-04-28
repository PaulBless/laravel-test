# Skill Test
This is an example application developed for an interview process using the Laravel web framework.

## Project Description
To create a very simple Laravel web application for task management: 
- Create task (info to save: task name, priority, timestamps) 
- Edit task 
- Delete task 
- Reorder tasks with drag and drop in the browser. Priority should automatically be updated based on this. #1 priority goes at top, #2 next down and so on. 
- Tasks should be saved to a mysql table.

BONUS POINT: add project functionality to the tasks. User should be able to select a project from a dropdown and only view tasks associated with that project.

You will be graded on how well-written & readable your code is, if it works, and if you did it the Laravel way.

## Installation & Setup
1 Clone the project from using this command:
    git clone https://github.com/PaulBless/taravel-task.git

2 After cloning the project, install the dependencies using the following command:
    composer install

3 Generate and install application key
    php artisan key:generate

4 Setup .env variables using this command: 
   copy .env.example .env (windows)
   sudo cp .env.exammple .env (linux)

5 Set your environment variables (ie database name, username and password)

6 Run database migration using following commnand:
    php artisan migrate

7 Run application
    php artisan serve


