import { NgModule } from "@angular/core";
import { Routes, RouterModule } from '@angular/router';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { SearchComponent } from "./pages/search/search.component";
import { AboutComponent } from './pages/about/about.component';
import { TaskComponent } from './pages/task/task.component';
import { ActivityComponent } from './pages/activity/activity.component';

const routes: Routes = [

  { path: 'dashboard', component: DashboardComponent },
  { path: 'about', component: AboutComponent },
  { path: 'task/:id', component: TaskComponent },
  { path: 'activity', component: ActivityComponent },
  { path: 'search/:termino', component: SearchComponent },
  { path: '**', pathMatch: 'full', redirectTo: 'dashboard' }

];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})

export class AppRoutingModule { }