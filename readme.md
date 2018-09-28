Quick Deleter

The Quick Deleter external module allows REDCap super users to quickly delete and/or undelete projects in bulk.  The table includes all the information you need to feel confident in deleting a project.

4 report types
1)  My projects:  Displays all the projects for the current user
2)  All projects:  Displays all projects
3)  json:  Export a json from the Admin Dashboard and paste it into quick deleter
4)  csv:  Paste a list of comma separated project ids (can have spaces or not)

-  The table features column sorting, filtering, and adjusting the amount of rows displayed. 

-  Green rows are active projects, red rows are projects flagged for delete.

-  When a project is "deleted" via Quick Deleter, it will merely be flagged for delete.  30 days after its flagged delete date, it will be permanently deleted.  

-  Checking the box in a green row will highlight the row red, indicating that you are going to delete the project.  Checking the box in a red row will highlight the row green, indicating that you are going to undelete the project.  Both delete and undelete can be done in the same submit.  The database is only queried once per submit.

-  The json and csv page feature a check all checkbox to highlight all the rows in the table.  This will highlight all rows in the query, not just the visible ones.  Be careful when using this feature, you may accidentally delete/undelete project you didn't intend to.  This feature is intentionally missing from the my projects and all project pages.

-  Many values in the table are links that will take you to various pages in the REDCap project:
    -  PID:  Project home
    -  Project name:  Project setup
    -  Status:  Other functionality
    -  Record count:  All data report
    -  Users:  User rights
    -  Last event date:  Logging
    -  Days since last event:  Logging 

-  To show only projects that are active, type "" in the "delete flagged" column filter.  To show only projects that are flagged for delete, type <> in the "delete flagged" column filter.  