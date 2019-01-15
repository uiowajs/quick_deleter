<?php

$sqlGetAllProjects = db_query(
    "
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        GROUP BY a.project_id
        ORDER BY a.project_id ASC
        ");  // End SQL Query


$sqlGetAllActiveProjects = db_query(
    "
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        WHERE a.date_deleted IS NULL
        GROUP BY a.project_id
        ORDER BY a.project_id ASC
        ");  // End SQL Query

$sqlGetAllDeletedProjects = db_query(
    "
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        WHERE a.date_deleted IS NOT NULL
        GROUP BY a.project_id
        ORDER BY a.project_id ASC
        ");  // End SQL Query

$sqlGetMyProjects = db_query(
"
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        WHERE username = ".USERID."
        GROUP BY a.project_id
        ORDER BY a.project_id ASC
        ");  // End SQL Query

$sqlGetMyActiveProjects = db_query(
    "
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        WHERE username = ".USERID." AND a.date_deleted IS NULL
        GROUP BY a.project_id
        ORDER BY a.project_id ASC
        ");  // End SQL Query

$sqlGetMyDeletedProjects = db_query(
    "
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        WHERE username = ".USERID." AND a.date_deleted IS NOT NULL
        GROUP BY a.project_id
        ORDER BY a.project_id ASC
        ");  // End SQL Query









$Project_Pages = array(
    array(
        "reportName" => "My Projects",
        "fileName" => "My_Projects",
        "description" => "List of projects for the current user.",
        "sql" => "
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        WHERE username = ".USERID."
        GROUP BY a.project_id
        ORDER BY a.project_id ASC  
        "
    ),
    array (
        "reportName" => "All Projects",
        "fileName" => "All_Projects",
        "description" => "List of all projects.",
        "sql" => "
        SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
        CAST(CASE a.status
             WHEN 0 THEN 'Development'
             WHEN 1 THEN 'Production'
             WHEN 2 THEN 'Inactive'
             WHEN 3 THEN 'Archived'
             ELSE a.status
             END AS CHAR(50)) AS 'Statuses',
        CAST(CASE a.purpose
            WHEN 0 THEN 'Practice / Just for fun'
            WHEN 4 THEN 'Operational Support'
            WHEN 2 THEN 'Research'
            WHEN 3 THEN 'Quality Improvement'
            WHEN 1 THEN 'Other'
            ELSE a.purpose
            END AS CHAR(50)) AS 'Purpose',
        CAST(creation_time AS date) AS 'New Creation Time', 
        CAST(a.date_deleted AS date) AS 'New Date Deleted', 
        CAST(last_logged_event AS date) AS 'New Last Event', 
        DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
        CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
        CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
        GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
        FROM redcap_projects as a
        JOIN redcap_user_rights AS b
        ON a.project_id=b.project_id
        JOIN redcap_record_counts AS c
        ON a.project_id=c.project_id
        GROUP BY a.project_id
        ORDER BY a.project_id ASC
        "
    )
);