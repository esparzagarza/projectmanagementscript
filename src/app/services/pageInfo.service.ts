import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { PageInfo } from '../interfaces/pageInfo.interface';

@Injectable({
  providedIn: 'root'
})
export class PageInfoService {

  info: PageInfo = {};
  cargada = false;

  constructor(private http: HttpClient) {
    this.loadInfo();
  }

  private loadInfo() {

    // Leer el archivo JSON
    this.http.get('assets/data/pageInfo.json')

      .subscribe((result: PageInfo) => {
        this.cargada = true;
        this.info = result;

      });
  }
}
