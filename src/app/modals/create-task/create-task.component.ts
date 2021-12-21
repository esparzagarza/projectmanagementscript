import { Component, OnInit } from '@angular/core';
import { NewTask } from 'src/app/models/new-task';
import { PageInfoService } from '../../services/pageInfo.service';
import { TasksService } from '../../services/tasks.service';
import { DatePipe } from '@angular/common';


@Component({
  selector: 'app-create-task',
  templateUrl: './create-task.component.html',
  providers: [DatePipe]
})
export class CreateTaskComponent implements OnInit {

  public newTask: NewTask = {};

  constructor(
    public _pageInfoService: PageInfoService,
    public _taskService: TasksService,
    private datePipe: DatePipe
  ) {
  }

  ngOnInit(): void { this.startUp(); }

  public startUp() {
    this.newTask = new NewTask();
    this.newTask.duedate = this.datePipe.transform(this.newTask.duedate, 'yyyy-MM-dd 12:00');
  }

  public saveForm(request: NewTask) {
    this._taskService.createTask(request)
      .subscribe(() => {
        this._taskService.dataBacklog();
        this.startUp();
      });
  }
}
