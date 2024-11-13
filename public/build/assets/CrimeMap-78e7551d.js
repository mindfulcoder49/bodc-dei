import{o as p,f,b as t,t as c,l as N,i as g,k as h,F as C,r as P,a as k,Q as T,m as v,p as U,q as B,g as F,s as O,v as R,x as Q,y as q,c as j,w as G}from"./app-d8717654.js";import{l as D}from"./leaflet-src-961d8190.js";import"./leaflet.markercluster-src-2d4af6ab.js";/* empty css                                                                          */import{_ as w}from"./_plugin-vue_export-helper-c27b6911.js";import{P as Y}from"./PageTemplate-a66ebae3.js";import"./GuestLayout-1c44c54a.js";import"./ResponsiveNavLink-f4e51f3c.js";import"./AuthenticatedLayout-c40e29bb.js";const z={name:"CrimeData",props:{crimeData:{type:Object,required:!0}},methods:{formatDate(m){const{month:d,hour:n,year:b}=m;return`${d}/${b} ${n}:00`}}},W={class:"crime-card p-4 border rounded-md shadow-md mb-4 bg-white hover:bg-gray-100 transition duration-300 ease-in-out"},H={class:"flex flex-col md:flex-row md:justify-between md:items-center"},K={class:"mb-2 md:mb-0"},J={class:"text-xl font-semibold mb-1"},X={class:"text-gray-600"},Z={class:"text-sm text-gray-500"},ee={class:"mt-2 text-sm text-gray-600"};function te(m,d,n,b,_,i){return p(),f("div",W,[t("div",H,[t("div",K,[t("h4",J,"Incident Number: "+c(n.crimeData.incident_number),1),t("p",X,"Offense: "+c(n.crimeData.offense_description),1)]),t("div",Z,[t("p",null,"District: "+c(n.crimeData.district),1),t("p",null,"Date: "+c(i.formatDate(n.crimeData)),1)])]),t("div",ee,[t("p",null,"Offense Code: "+c(n.crimeData.offense_code),1),t("p",null,"Reporting Area: "+c(n.crimeData.reporting_area),1),t("p",null,"Shooting: "+c(n.crimeData.shooting?"Yes":"No"),1),t("p",null,"Street: "+c(n.crimeData.street),1),t("p",null,"Location: "+c(n.crimeData.location),1),t("p",null,"Offense Category: "+c(n.crimeData.offense_category),1)])])}const ae=w(z,[["render",te],["__scopeId","data-v-b5041b7c"]]),oe={name:"CrimeDataList",components:{CrimeData:ae},props:{filteredCrimeData:{type:Array,required:!0},itemsPerPage:{type:Number,default:10}},data(){return{currentPage:1,inputPage:1}},computed:{totalPages(){return Math.ceil(this.filteredCrimeData.length/this.itemsPerPage)},paginatedCrimeData(){const m=(this.currentPage-1)*this.itemsPerPage,d=m+this.itemsPerPage;return this.filteredCrimeData.slice(m,d)}},watch:{currentPage(m){this.inputPage=m}},methods:{nextPage(){this.currentPage<this.totalPages&&this.currentPage++},prevPage(){this.currentPage>1&&this.currentPage--},goToPage(){this.inputPage>=1&&this.inputPage<=this.totalPages?this.currentPage=this.inputPage:this.inputPage=this.currentPage}}},se={class:"flex justify-between items-center mt-4"},le=["disabled"],ne=t("span",null,"Page ",-1),re=["max"],ie=["disabled"],de={class:"pl-5"},ue={key:0,class:"text-gray-500"},ce={key:1,class:"text-gray-500"};function me(m,d,n,b,_,i){const o=N("CrimeData");return p(),f("div",null,[t("div",se,[t("button",{onClick:d[0]||(d[0]=(...u)=>i.prevPage&&i.prevPage(...u)),disabled:_.currentPage===1,class:"p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"},"Previous",8,le),t("div",null,[ne,g(t("input",{"onUpdate:modelValue":d[1]||(d[1]=u=>_.inputPage=u),onChange:d[2]||(d[2]=(...u)=>i.goToPage&&i.goToPage(...u)),type:"number",min:"1",max:i.totalPages,class:"w-16 p-1 border rounded-md text-center"},null,40,re),[[h,_.inputPage,void 0,{number:!0}]]),t("span",null," of "+c(i.totalPages),1)]),t("button",{onClick:d[3]||(d[3]=(...u)=>i.nextPage&&i.nextPage(...u)),disabled:_.currentPage===i.totalPages,class:"p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"},"Next",8,ie)]),t("ul",de,[i.paginatedCrimeData.length===0?(p(),f("li",ue,"No results found")):(p(),f("li",ce,"Number of results: "+c(n.filteredCrimeData.length),1)),(p(!0),f(C,null,P(i.paginatedCrimeData,(u,S)=>(p(),f("li",{key:S},[k(o,{crimeData:u},null,8,["crimeData"])]))),128))])])}const pe=w(oe,[["render",me]]),r=m=>(Q("data-v-e837ca49"),m=m(),q(),m),fe={class:""},ge=r(()=>t("h3",{class:"text-2xl font-semibold mb-4"},"Interactive Boston Crime Map",-1)),be=r(()=>t("div",{id:"map",class:"h-[70vh] mb-6"},null,-1)),_e=r(()=>t("h4",{class:"text-lg font-semibold mb-4"},"Natural Language Query",-1)),ve=r(()=>t("p",{class:"mb-4"},"Enter a natural language query to filter the crime data:",-1)),he={class:"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"},ye={key:0,class:"p-2 border rounded-md w-full mb-4 overflow-scroll",rows:"5",readonly:""},xe=r(()=>t("h4",{class:"text-lg font-semibold mb-4"},"Or Use Manual Filters",-1)),Ce=r(()=>t("p",{class:"mb-4"},"Use the manual filters below to filter the crime data:",-1)),De={class:"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"},Pe={class:"flex flex-col"},we=r(()=>t("label",{for:"offenseCategory",class:"font-medium mb-1"},"Choose Offense Categories:",-1)),Se=r(()=>t("option",{value:""},"All",-1)),Oe=["value"],ke={class:"flex flex-col"},$e=r(()=>t("label",{for:"district",class:"font-medium mb-1"},"Choose Districts:",-1)),Le=r(()=>t("option",{value:""},"All",-1)),Ae=["value"],Me={class:"flex flex-col"},Ve=r(()=>t("label",{for:"year",class:"font-medium mb-1"},"Choose Years:",-1)),Ee=r(()=>t("option",{value:""},"All",-1)),Ie=["value"],Ne={class:"flex flex-col"},Te=r(()=>t("label",{for:"offenseCodes",class:"font-medium mb-1"},"Enter Offense Codes (comma separated):",-1)),Ue={class:"flex flex-col"},Be=r(()=>t("label",{for:"startDate",class:"font-medium mb-1"},"Start Date:",-1)),Fe={class:"flex flex-col"},Re=r(()=>t("label",{for:"endDate",class:"font-medium mb-1"},"End Date:",-1)),Qe={class:"flex flex-col"},qe=r(()=>t("label",{for:"shooting",class:"font-medium mb-1"},"Shooting:",-1)),je={class:"flex flex-col"},Ge=r(()=>t("label",{for:"limit",class:"font-medium mb-1"},"Record Limit:",-1)),Ye={class:"mt-6"},ze=r(()=>t("h4",{class:"text-lg font-semibold mb-4"},"List of Crime Data Points",-1)),We={__name:"CrimeMapComponent",setup(m){const{props:d}=T(),n=v(null),b=v(null),_=v(d.crimeData||[]),i=v([]),o=v({offense_codes:"",offense_category:[],district:[],start_date:"",end_date:"",year:[],limit:1500,shooting:!1}),u=v("");axios.defaults.headers.common["X-CSRF-TOKEN"]=document.querySelector('meta[name="csrf-token"]').getAttribute("content");const S=v([{value:"murder_and_manslaughter",label:"Murder and Manslaughter"},{value:"rape",label:"Rape"},{value:"robbery",label:"Robbery"},{value:"assault",label:"Assault"},{value:"burglary",label:"Burglary"},{value:"larceny",label:"Larceny"},{value:"auto_theft",label:"Auto Theft"},{value:"simple_assault",label:"Simple Assault"},{value:"arson",label:"Arson"},{value:"forgery_counterfeiting",label:"Forgery and Counterfeiting"},{value:"fraud",label:"Fraud"},{value:"embezzlement",label:"Embezzlement"},{value:"stolen_property",label:"Stolen Property"},{value:"vandalism",label:"Vandalism"},{value:"weapons_violations",label:"Weapons Violations"},{value:"prostitution",label:"Prostitution"},{value:"sex_offenses",label:"Sex Offenses"},{value:"drug_violations",label:"Drug Violations"},{value:"gambling",label:"Gambling"},{value:"child_offenses",label:"Child Offenses"},{value:"alcohol_violations",label:"Alcohol Violations"},{value:"disorderly_conduct",label:"Disorderly Conduct"},{value:"kidnapping",label:"Kidnapping"},{value:"miscellaneous_offenses",label:"Miscellaneous Offenses"},{value:"vehicle_laws",label:"Vehicle Laws"},{value:"investigations",label:"Investigations"},{value:"other_services",label:"Other Services"},{value:"property",label:"Property"},{value:"disputes",label:"Disputes"},{value:"animal_incidents",label:"Animal Incidents"},{value:"missing_persons",label:"Missing Persons"},{value:"other_reports",label:"Other Reports"},{value:"accidents",label:"Accidents"}]),L=["A1","A15","A7","B2","B3","C11","C6","D14","D4","E13"],A=["2024","2023","2022","2021","2020","2019","2018","2017"];U(()=>{n.value=D.map("map").setView([42.3601,-71.0589],13),D.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(n.value),b.value=D.markerClusterGroup(),n.value.addLayer(b.value),y()});const y=async()=>{try{const a=(await axios.post("/api/crime-data",{filters:o.value})).data;b.value.clearLayers(),i.value=a.crimeData,a.crimeData.forEach(e=>{if(e.lat&&e.long){const x=`
          <div>
            <strong>Incident Number:</strong> ${e.incident_number}<br>
            <strong>Offense Code:</strong> ${e.offense_code}<br>
            <strong>Offense Code Group:</strong> ${e.offense_code_group}<br>
            <strong>Offense Description:</strong> ${e.offense_description}<br>
            <strong>District:</strong> ${e.district}<br>
            <strong>Reporting Area:</strong> ${e.reporting_area}<br>
            <strong>Shooting:</strong> ${e.shooting?"Yes":"No"}<br>
            <strong>Occurred On:</strong> ${new Date(e.occurred_on_date).toLocaleString()}<br>
            <strong>Year:</strong> ${e.year}<br>
            <strong>Month:</strong> ${e.month}<br>
            <strong>Day of Week:</strong> ${e.day_of_week}<br>
            <strong>Hour:</strong> ${e.hour}<br>
            <strong>UCR Part:</strong> ${e.ucr_part}<br>
            <strong>Street:</strong> ${e.street}<br>
            <strong>Location:</strong> ${e.location}<br>
            <strong>Offense Category:</strong> ${e.offense_category}
          </div>
        `,l=D.marker([e.lat,e.long]);l.bindPopup(x),b.value.addLayer(l)}})}catch(s){console.error("Failed to fetch crime data",s)}},$=s=>typeof s=="string"&&(s=s.replace(/[\r\n]+/g," "),s.includes(","))?`"${s.replace(/"/g,'""')}"`:s,M=()=>{const s=[["Incident Number","Offense Code","Offense Code Group","Offense Description","District","Reporting Area","Shooting","Occurred On Date","Year","Month","Day of Week","Hour","UCR Part","Street","Lat","Long","Location","Offense Category"].map($).join(","),...i.value.map(l=>[l.incident_number,l.offense_code,l.offense_code_group,l.offense_description,l.district,l.reporting_area,l.shooting?"Yes":"No",new Date(l.occurred_on_date).toLocaleString(),l.year,l.month,l.day_of_week,l.hour,l.ucr_part,l.street,l.lat,l.long,l.location,l.offense_category].map($).join(","))].join(`
`),a=new Blob([s],{type:"text/csv;charset=utf-8;"}),e=document.createElement("a"),x=URL.createObjectURL(a);e.setAttribute("href",x),e.setAttribute("download","crime_data.csv"),e.style.visibility="hidden",document.body.appendChild(e),e.click(),document.body.removeChild(e)},V=s=>{const a=new Date(s),e=a.getFullYear(),x=String(a.getMonth()+1).padStart(2,"0"),l=String(a.getDate()).padStart(2,"0");return`${e}-${x}-${l}`},E=async()=>{try{document.getElementById("submitQuery").innerText="Loading...",document.getElementById("submitQuery").disabled=!0;const a=(await axios.post("/api/natural-language-query",{query:u.value})).data;_.value=a.crimeData,Object.keys(o.value).forEach(e=>{a.filters.hasOwnProperty(e)?e==="start_date"||e==="end_date"?o.value[e]=V(a.filters[e]):o.value[e]=a.filters[e]:o.value[e]=""}),document.getElementById("submitQuery").innerText="Submit to GPT-4o-mini",document.getElementById("submitQuery").disabled=!1,y()}catch(s){document.getElementById("submitQuery").innerText="Submit to GPT-4o-mini",document.getElementById("submitQuery").disabled=!1,console.error("Failed to process natural language query",s)}},I=()=>{Object.keys(o.value).forEach(s=>{s==="offense_category"||s==="district"||s==="year"?o.value[s]=[]:s==="shooting"?o.value[s]=!1:o.value[s]=""}),y()};return B(o,y,{deep:!0}),(s,a)=>(p(),f(C,null,[t("div",fe,[ge,be,_e,ve,t("div",he,[t("button",{onClick:a[0]||(a[0]=e=>u.value="All the fraud that happened last week"),class:"p-2 border rounded-md w-full mb-4"},"All the fraud that happened last week"),t("button",{onClick:a[1]||(a[1]=e=>u.value="Last month's welfare checks"),class:"p-2 border rounded-md w-full mb-4"},"Last month's welfare checks"),t("button",{onClick:a[2]||(a[2]=e=>u.value="Todo el robo que ocurrió el mes pasado"),class:"p-2 border rounded-md w-full mb-4"},"Todo el robo que ocurrió el mes pasado")]),g(t("input",{"onUpdate:modelValue":a[3]||(a[3]=e=>u.value=e),type:"text",placeholder:"Example: All the fraud that happened last week",class:"p-2 border rounded-md w-full mb-4"},null,512),[[h,u.value]]),t("button",{onClick:E,id:"submitQuery",class:"p-2 bg-blue-500 text-white rounded-md mb-4"},"Submit to GPT-4o-mini"),o.value?(p(),f("pre",ye,c(JSON.stringify(o.value,null,2)),1)):F("",!0),xe,Ce,t("div",De,[t("div",Pe,[we,g(t("select",{"onUpdate:modelValue":a[4]||(a[4]=e=>o.value.offense_category=e),multiple:"",class:"p-2 border rounded-md"},[Se,(p(!0),f(C,null,P(S.value,e=>(p(),f("option",{key:e.value,value:e.value},c(e.label),9,Oe))),128))],512),[[O,o.value.offense_category]])]),t("div",ke,[$e,g(t("select",{"onUpdate:modelValue":a[5]||(a[5]=e=>o.value.district=e),multiple:"",class:"p-2 border rounded-md"},[Le,(p(),f(C,null,P(L,e=>t("option",{key:e,value:e},c(e),9,Ae)),64))],512),[[O,o.value.district]])]),t("div",Me,[Ve,g(t("select",{"onUpdate:modelValue":a[6]||(a[6]=e=>o.value.year=e),multiple:"",class:"p-2 border rounded-md"},[Ee,(p(),f(C,null,P(A,e=>t("option",{key:e,value:e},c(e),9,Ie)),64))],512),[[O,o.value.year]])]),t("div",Ne,[Te,g(t("input",{"onUpdate:modelValue":a[7]||(a[7]=e=>o.value.offense_codes=e),class:"p-2 border rounded-md",placeholder:"1103, 1104"},null,512),[[h,o.value.offense_codes]])]),t("div",Ue,[Be,g(t("input",{type:"date","onUpdate:modelValue":a[8]||(a[8]=e=>o.value.start_date=e),class:"p-2 border rounded-md"},null,512),[[h,o.value.start_date]])]),t("div",Fe,[Re,g(t("input",{type:"date","onUpdate:modelValue":a[9]||(a[9]=e=>o.value.end_date=e),class:"p-2 border rounded-md"},null,512),[[h,o.value.end_date]])]),t("div",Qe,[qe,g(t("input",{type:"checkbox","onUpdate:modelValue":a[10]||(a[10]=e=>o.value.shooting=e),class:"p-2 border rounded-md"},null,512),[[R,o.value.shooting]])]),t("div",je,[Ge,g(t("input",{type:"number","onUpdate:modelValue":a[11]||(a[11]=e=>o.value.limit=e),class:"p-2 border rounded-md"},null,512),[[h,o.value.limit]])])]),t("button",{onClick:y,class:"mt-4 p-2 bg-blue-500 text-white rounded-md"},"Submit Filters"),t("button",{onClick:I,class:"m-4 p-2 bg-blue-500 text-white rounded-md"},"Clear Filters")]),t("div",Ye,[ze,t("button",{onClick:M,class:"p-2 bg-green-500 text-white rounded-md mb-4"},"Download as CSV"),k(pe,{filteredCrimeData:i.value},null,8,["filteredCrimeData"])])],64))}},He=w(We,[["__scopeId","data-v-e837ca49"]]);const Ke={__name:"CrimeMap",setup(m){return(d,n)=>(p(),j(Y,null,{default:G(()=>[k(He)]),_:1}))}},nt=w(Ke,[["__scopeId","data-v-4ee5e173"]]);export{nt as default};
