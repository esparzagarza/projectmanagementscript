import { DatePipe } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NewTask } from 'src/app/models/new-task';
import { PageInfoService } from 'src/app/services/pageInfo.service';
import { TasksService } from 'src/app/services/tasks.service';

@Component({
  selector: 'app-edit-task',
  templateUrl: './edit-task.component.html',
  providers: [DatePipe]
})
export class EditTaskComponent implements OnInit {

  public editingTask: NewTask = {};

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    public _taskService: TasksService,
    public _pageInfoService: PageInfoService,
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

  public saveForm(request: NewTask) { this._taskService.editTask(request).subscribe((response: any) => { this.router.navigate(['/dashboard']); }); }

}
