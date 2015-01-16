ToDoLister
==========
This is yet another productivity app project.

Projects and labels
-------------------

#project_name ... can be only one project
@label_name ... task can have more than one label
!1 means that the task is top level and may have subtasks (optional)
!x where x > 1 means that task is a subtask


Deadline styles
---------------

*Class is implemented, newer formats may be added*

### Date based formatting

- 19&#46; 11&#46; 2014
- 19&#46; 11&#46; 14
- 19 Nov 2014
- 19 November 2014
- 11/19/14

#### specific time

- 6:00 19. 11. 2014
- 6:00 19. 11. 14
- 6:00 19 Nov 2014
- 6:00 19 November 2014
- 6:00 11/19/14

### Repeatable tasks

- ev(ery) day (@ 6:00)
- ev(ery) Mon(day) (@ 6:00)
- ev(ery) other day (@ 6:00)
- ev(ery) odd/even day (@ 6:00)
 * 1 January is odd day, 2 January is even 
- ev(ery) 3 days (@ 6:00)
- ev(ery) 25 (@ 6:00)
 * this means every 25th day of the month. If month has no such day this task will not be performed that month
 
 Templates
 ---------
 
 *To be implemented!*
 
 ### Set of tasks
 
 &task_name #project_name @some_label !x d-y
 
 example:
 
 &task_name #project_name @some_label !1 d+0
 
 &task2 #project_name @some_label !2 d+1
 
 &task3 #project_name @some_label !3 d+2
 
 
 
 ### Project template
 
 new #project
 
 &task_name #project_name @some_label !x
 
 
