import { DatePipe } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NewTask } from 'src/app/models/new-task';
import { TasksService } from 'src/app/services/tasks.service';

@Component({
  selector: 'app-delete-task',
  templateUrl: './delete-task.component.html',
  providers: [DatePipe]
})
export class DeleteTaskComponent implements OnInit {

  public editingTask: NewTask = {};

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    public _taskService: TasksService,
    public datePipe: DatePipe
  ) { }

  ngOnInit(): void { this.startUp(); }

  public startUp() {
    this.route.params
      .subscribe(item => {
        this._taskService.getOneTask(item['id']).subscribe((response: any) => {
          this.editingTask = response.data;
          this.editingTask.duedate = this.datePipe.transform(this.editingTask.duedate, 'yyyy-MM-dd HH:mm:ss');
        });
      });
  }

  public deleteTask() { this._taskService.deleteTask(this.editingTask).subscribe((response: any) => { this.router.navigate(['/dashboard']); }); }

}
