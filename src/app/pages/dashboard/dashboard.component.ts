import { Component, OnInit } from '@angular/core';
import { TasksService } from 'src/app/services/tasks.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html'
})
export class DashboardComponent implements OnInit {

  constructor(
    public _tasksService: TasksService,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void { this.route.params.subscribe(() => { this._tasksService.dataBacklog(); }); }

}
