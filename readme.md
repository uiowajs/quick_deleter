Quick Deleter

The Quick Deleter external module allows REDCap super users to quickly delete and/or restore projects in bulk.  The table includes all the information you need to feel confident in deleting a project.

3 report types
1)  My projects:  Displays all the projects for the current user
2)  All projects:  Displays all projects
3)  Custom:  Paste in a json from the Admin Dashboard or comma separated project IDs.  The csv can have spaces or not.

-  The table features column sorting, filtering, and adjusting the amount of rows displayed. 

-  Green rows are active projects, red rows are projects flagged for delete.

-  To show only projects that are active, type "" in the "delete flagged" column filter.  To show only projects that are flagged for delete, type <> in the "delete flagged" column filter.  

-  Checking the box in a green row will highlight the row red, indicating that you are going to delete the project.  Checking the box in a red row will highlight the row green, indicating that you are going to restore the project.  Both delete and restore can be done in the same submit.

-  When a project is "deleted" via Quick Deleter, it isn't delete immediately, it will merely be flagged for delete.  The project will be permanently deleted 30 days after its flagged for delete date.  

-  The custom json and csv page feature a check all checkbox.  This will highlight all rows in the query, not just the visible ones.  Be careful when using this feature, you may accidentally delete/restore projects you didn't intend to.  This feature is intentionally missing from the my projects and all project pages.

-  Project deletes and restores are logged both at the project and system level.

-  Many values in the table are links that will take you to various pages in the REDCap project:
    -  PID:  Project settings
    -  Project name:  Project setup
    -  Status:  Other functionality
    -  Record count:  All data report
    -  Users:  User rights
    -  Days since last event:  Logging 