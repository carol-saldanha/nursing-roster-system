# Nurse Rostering

## Challenge

You just started work for a startup that sells medical rostering software. They’ve just landed a new client, a major hospital, and everyone’s excited! However, the HR policies at the hospital are too complex for the software to handle.

At the hospital nurses work a rotating shift system. There are three work shifts per day; a morning shift, an evening shift and a night shift. Each day, all three shifts need to be filled.

Your task is to develop a rostering system that implements the following policies:

- Five nurses need to be on staff for each shift.
- Nurses must not be expected to work more than one shift per day.

The rostering system should calculate and output the nursing roster for any time period (specified by a start and end date).

A list of rosterable nurses has been provided in the file `storage/app/sample_data/nurses.json`.

## Scaffold Code

Some code has already been provided to help save you time. This focusses on the following areas.

1. Providing a command line interface so that this app can be called with parameters and provide help information.
2. Handling of input and output, such as parsing a file of nurses, and formatting the resulting roster to text, for printing to standard out.
3. A few basic data classes such as Roster and Nurse, mainly provided so that the input and output handling code has something to work with.

You may want to start by looking at the `RosterBuilderRepository` class which is intended to do the work of creating the Roster.

`RosterBuilderRepository` does the below:

1. Creates an empty roster if no nurses are provided.
2. Iterates through start and end dates and shift types to:
Allocate 5 nurses to a shift while also ensuring that one nurse doesn't work more than one shift a day.
3. Generates a file and puts the rostered result in `sample_data/roster.json`.
## Running

This app is designed to be from the command line using:

```
php artisan app:generate-roster --help
```

Doing so with no params should print out usage information.

Below is a test usage how to run the app:

`php artisan 'sample_data/nurses.json' '2024-01-01' '2024-01-05'`

Doing so, will output the roster for the given nurses for the timeframe. The code ensure one nurse does not work more than one shift a day while assigning 5 nurses to a shift (Morning, Evening, Night).

## Tests

You can run tests with `php artisan test`. Tests for the scaffold code have been provided.

`testBuildRoster` assures $roster is of type collection and also checks that each shift in the roster has exactly 5 nurses assigned

`testBuildRosterWithEmptyNursesCollection` checks if the roster is empty if no nurses are provided (In case the file is empty.)

`testBuildRosterCheckMinimumNurses` checks if there are less than 15 nurses, because one day requires a minimum of 15 nurses.

