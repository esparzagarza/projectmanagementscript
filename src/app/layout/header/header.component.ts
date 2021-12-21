import { Component, OnInit } from '@angular/core';
import { PageInfoService } from '../../services/pageInfo.service';


@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
})
export class HeaderComponent {

  constructor(public _pageInfoService: PageInfoService,) { }
}
