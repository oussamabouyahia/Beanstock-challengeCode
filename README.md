# I- My Understanding and Approach

# 1- create new laravel app

# 2-database architecture

-include the two data tables inside my storage
-create tables (migration and models) for the two tables 'encadrement_logement' and 'quartier paris'
-seeding the two tables with the csv files accordingly
Note: I will use the sqlite by default , switching to MySQL/Postgres is straightforward , I will just adjust the `.env` file

# 3 create the endpoint route

# 4 create controller to handle the logic

-   validate inputs
-   get the required result according to the user inputs
-   return the requiment response (likely json response) (min, average, max)

# 5 Tests:

-unit test for input validation and other steps
-feature test for the controller logic

# 6-Documentation

a dedicated branch "feature/rent-range-insights"
each step will be committed accordingly
code will include clear comments and meangfull variables/functions naming
