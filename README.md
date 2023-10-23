# Rover Control System

This project is responsible for remotely controlling rovers deployed on Jupiter's surface. It translates commands transmitted from Earth into instructions comprehensible to the rover, ensuring obstacle detection and accurate movement.

## Folder Structure
```
.
├── README.MD
├── composer.json
├── composer.lock
├── phpunit.xml
├── src - Contains the source code of the project.
│   ├── Domain
│   │   ├── Exceptions
│   │   │   └── ObstacleEncounteredException.php
│   │   ├── Input
│   │   │   ├── InputData.php
│   │   │   ├── InputGetter.php
│   │   │   ├── InputHandler.php
│   │   │   └── StandardInputGetter.php
│   │   └── Navigation
│   │       ├── Command.php
│   │       ├── Coordinate.php
│   │       ├── Direction.php
│   │       ├── Position.php
│   │       ├── Rover.php
│   │       └── World.php
│   └── public
│       └── index.php
├── tests - Contains the unit and integration tests for the project.
│   ├── integration
│   │   └── public
│   │       └── IntegrationTest.php
│   └── unit
│       └── Domain
│           ├── Input
│           │   └── InputHandlerTest.php
│           └── Navigation
│               ├── PositionTest.php
│               ├── RoverTest.php
│               └── WorldTest.php
└── vendor - Contains vendor source code
  
  ```
## Requirements

- PHP version 8.1 or above.
- Composer version 2.55 or above.


## How to Run the Project

Before testing the project, you'll need to set it up and run it. Follow these steps:

1. **Clone the Repository:**
   - To clone the repository to your local machine, use the following command:
     ```
     git clone https://github.com/stacytonui/rover-control.git
     ```

2. **Install Dependencies:**
   - Make sure you have [Composer](https://getcomposer.org/) installed.
   - Run the following command to install project dependencies:
     ```
     composer install
     ```

3. **Start the Application:**
   - Use the following command to start the application:
     ```
     php src/public/index.php
     ```
   - This will initiate the application and provide instructions for further interactions.
   

   ![run-rover](https://github-production-user-asset-6210df.s3.amazonaws.com/57434209/277272459-52e38775-c402-4925-8169-dec5ecbb0d86.png)
## How to Test

To run the tests for the Rover Control System, follow these steps:
1. **Run Tests:**
    - Use the following command to run the PHPUnit tests:
      ```
      vendor/bin/phpunit
      ```

    - This will execute both unit and integration tests.
   
![run-rover](https://github.com/stacytonui/rover-control/assets/57434209/0d8c7020-af49-4fe1-ae9d-330f1c4838c9)
