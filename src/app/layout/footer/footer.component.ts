import { Component, OnInit } from '@angular/core';
import { PageInfoService } from 'src/app/services/pageInfo.service';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css']
})
export class FooterComponent implements OnInit {

  constructor(public _pageInfoService: PageInfoService) { }

  ngOnInit(): void {
  }

}
