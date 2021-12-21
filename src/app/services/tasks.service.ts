import { Injectable } from '@angular/core';
import { Task } from '../interfaces/task.interface';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { NewTask } from '../models/new-task';
import { environment } from 'src/environments/environment';

@Injectable({ providedIn: 'root' })

export class TasksService {

  // replace your backend path here...
  baseUri = "http://Backend/index.php";

  
  allData: Task[] = [];
  filteredData: any = { "started": {}, "inprogress": {}, "completed": {} };
  httpOptionsPlain = {
    headers: new HttpHeaders({
      "Accept": "text/plain",
      "Content-Type": "text/plain"
    }),
    "responseType": "text"
  };

  constructor(private http: HttpClient) { }

  private loadData() {
    return new Promise((resolve, reject) => {
      this.http.get(this.baseUri)
        .subscribe((response: any) => {
          this.allData = response.data.reverse().slice(0, 12);
          resolve(true);
        });
    });
  }

  public dataBacklog() { this.loadData().then(() => { this.filterBacklog() }); }

  private filterBacklog() {
    this.filteredData.started = this.allData.filter(i => i.status == 'started');
    this.filteredData.inprogress = this.allData.filter(i => i.status == 'inprogress');
    this.filteredData.completed = this.allData.filter(i => i.status == 'completed');
  }

  public getAllTasks() { return this.http.get(this.baseUri); };

  public getOneTask(id: string) { return this.http.get(this.baseUri, { params: { "action": "getOne", "id": id } }); }

  public createTask(task: NewTask) { return this.http.post(this.baseUri, task); }

  public editTask(task: NewTask) { return this.http.put(this.baseUri, task); }

  public deleteTask(task: NewTask) { return this.http.delete(this.baseUri, { body: task, }); }
}
