import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { TasksService } from '../../services/tasks.service';
import { PageInfoService } from 'src/app/services/pageInfo.service';
import { DatePipe } from '@angular/common';
import { Task } from 'src/app/interfaces/task.interface';


@Component({
  selector: 'app-task',
  templateUrl: './task.component.html',
  providers: [DatePipe]
})
export class TaskComponent implements OnInit {

  public selectedTask: Task = {};
  public overdue: boolean = false;

  constructor(
    private route: ActivatedRoute,
    public _taskService: TasksService,
    public _pageInfoService: PageInfoService,
    public datePipe: DatePipe
  ) { }

  ngOnInit(): void {
    this.route.params
      .subscribe(item => {
        this._taskService.getOneTask(item['id']).subscribe((response: any) => {
          this.selectedTask = response.data;
          const currentDate = new Date();
          const dueDate = new Date(response.data.duedate);
          this.overdue = currentDate > dueDate ? true : false;
        });
      });
  }
}
