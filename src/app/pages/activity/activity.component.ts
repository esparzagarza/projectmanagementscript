import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { PageInfoService } from 'src/app/services/pageInfo.service';
import { TasksService } from 'src/app/services/tasks.service';
import { Task } from '../../interfaces/task.interface';

@Component({
  selector: 'app-activity',
  templateUrl: './activity.component.html'

})
export class ActivityComponent implements OnInit {

  public allData: Task[] = [];
  public backlogData: Task[] = [];
  public filteredData: Task[] = [];
  public selectedFilter: any = {};

  constructor(
    public _tasksService: TasksService,
    private route: ActivatedRoute,
    public _pageInfoService: PageInfoService,
  ) { }

  public statusColor(status: any) {
    let result;
    switch (status) {
      case "started": result = "badge-light-warning"; break;
      case "inprogress": result = "badge-light-success"; break;
      case "completed": result = "badge-light-primary"; break;
      default: result = "badge-light-warning"; break;
    }
    return result;
  }

  public notEmptyFilteredData() { if (this.filteredData.length == 0) this.filteredData = this.backlogData; }

  public isIndexOfTxt(value1: string, value2: string) { return value1.toLocaleLowerCase().indexOf(value2) > 0 ? true : false }

  public applyTxt(search: string, array: Task[]) {

    search = search.toLocaleLowerCase();

    this.filteredData = array.filter((item: any) =>
      this.isIndexOfTxt(item.name, search)
      || this.isIndexOfTxt(item.description, search)
      || this.isIndexOfTxt(item.tags, search)
      || this.isIndexOfTxt(item.prio, search)
      || this.isIndexOfTxt(item.duedate, search)
    );

    this.notEmptyFilteredData();
  }

  public searchByFilter() {

    this.filteredData = [];

    if (this.selectedFilter.type && !this.selectedFilter.status) this.filteredData = this.allData.filter((item) => item.type == this.selectedFilter.type);
    if (!this.selectedFilter.type && this.selectedFilter.status) this.filteredData = this.allData.filter((item) => item.status == this.selectedFilter.status);
    if (this.selectedFilter.type && this.selectedFilter.status) this.filteredData = this.allData.filter((item) => item.type == this.selectedFilter.type && item.status == this.selectedFilter.status);

    if (this.selectedFilter.txtSearch) this.applyTxt(this.selectedFilter.txtSearch, this.filteredData);

    this.notEmptyFilteredData();
  }

  public searchByHuge() {

    if (this.selectedFilter.txtHuge) this.applyTxt(this.selectedFilter.txtHuge, this.allData);

    else this.loadData();

  }

  public resetFilter() { this.selectedFilter = {}; this.loadData(); }

  public loadData() {
    this.route.params
      .subscribe(() => {
        this._tasksService.getAllTasks()
          .subscribe((response: any) => {
            this.allData = response.data;
            this.backlogData = response.data.reverse().slice(0, 12);
            this.filteredData = this.backlogData;
          });
      });

    this.notEmptyFilteredData();
  }

  ngOnInit(): void { this.loadData(); }

}
