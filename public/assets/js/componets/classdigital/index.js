// index.js
import {getInputdata} from './getdatainput.js';
import {Simpandatainput} from './simpandatainput.js';
import {getDatalist} from  './listdata.js';
import{Updatedatainput} from './updatedatainput.js';

$(document).ready(function () {
   
const url = new URL(window.location.href);
const pathSegments = url.pathname.split("/");
const lastSegment = pathSegments.filter(Boolean).pop(); // filter untuk hilangkan elemen kosong
// Kondisi berdasarkan segmen terakhir URL

if(lastSegment !=="listdigital"){
  getInputdata();
  Simpandatainput();
}else{
   getDatalist();
   Updatedatainput();
}

});


