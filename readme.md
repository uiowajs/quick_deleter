Quick Deleter

The Quick Deleter external module allows REDCap super users to quickly delete and/or restore projects.  The table includes all the information you need to feel confident in deleting a project.

4 report types
1)  My projects:  Displays all the projects for the current user
2)  All projects:  Displays all projects
3)  Custom json:  Paste in a json from the Admin Dashboard 
4)  Custom csv:  Comma separated project IDs.  The csv can have spaces or not.

*  The custom report is limited to 100 projects.

-  The table features column sorting, filtering, and adjusting the amount of rows displayed. 

-  To show only projects that are active, type "" in the "delete flagged" column filter.  To show only projects that are flagged for delete, type <> in the "delete flagged" column filter.  

-  Like deleting a project in REDCap, deleting via Quick Deleter won't permanently delete the project immediately.  The project will be restoreable for 30 days, then be permanently deleted.

-  Project deletes and restores are logged both at the project and system level.

-  

-  Many values in the table are links that will take you to various pages in the REDCap project:
    -  PID:  Project settings
    -  Project name:  Project setup
    -  Status:  Other functionality
    -  Record count:  All data report
    -  Users:  User page in control center
    -  Days since last event:  Logging 
    
-  Configuration options:
    - Hide action column 
    - Replace project buttons with checkboxes 
    - Enable row colors. Red for deleted, green for active projects. 
    - Enable delete and restore project button colors. Red for delete, green for restore. 
    - Enable checkbox submit button color. Red for delete only, green for restore only, gray for delete and restore.
    
