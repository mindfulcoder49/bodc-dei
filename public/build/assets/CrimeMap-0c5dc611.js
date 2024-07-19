import{K as F,l as u,m as T,p as q,o as n,f as r,b as t,i as d,k as x,t as i,g as N,q as c,F as p,r as f,s as I,x as P,c as B,w as W,a as Y}from"./app-1d3c8903.js";import{l as w}from"./CrimeMapComponent.vue_vue_type_style_index_0_scoped_54cefa39_lang-31ff1dab.js";import{_ as S}from"./_plugin-vue_export-helper-c27b6911.js";import{P as G}from"./PageTemplate-566a1633.js";import"./GuestLayout-c00eb68b.js";import"./ResponsiveNavLink-806ca0ac.js";import"./AuthenticatedLayout-360e6587.js";const l=v=>(I("data-v-54cefa39"),v=v(),P(),v),Q={class:""},R=l(()=>t("h3",{class:"text-2xl font-semibold mb-4"},"Interactive Boston Crime Map",-1)),H=l(()=>t("div",{id:"map",class:"h-[70vh] mb-6"},null,-1)),K=l(()=>t("h4",{class:"text-lg font-semibold mb-4"},"Natural Language Query",-1)),j=l(()=>t("p",{class:"mb-4"},"Enter a natural language query to filter the crime data:",-1)),z={class:"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"},J={key:0,class:"p-2 border rounded-md w-full mb-4 overflow-scroll",rows:"5",readonly:""},X=l(()=>t("h4",{class:"text-lg font-semibold mb-4"},"Or Use Manual Filters",-1)),Z=l(()=>t("p",{class:"mb-4"},"Use the manual filters below to filter the crime data:",-1)),ee={class:"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"},te={class:"flex flex-col"},oe=l(()=>t("label",{for:"offenseCategory",class:"font-medium mb-1"},"Choose an Offense Category:",-1)),se=l(()=>t("option",{value:""},"All",-1)),ae=["value"],le={class:"flex flex-col"},ne=l(()=>t("label",{for:"district",class:"font-medium mb-1"},"Choose a District:",-1)),re=l(()=>t("option",{value:""},"All",-1)),de=["value"],ue={class:"flex flex-col"},ie=l(()=>t("label",{for:"year",class:"font-medium mb-1"},"Choose a Year:",-1)),ce=l(()=>t("option",{value:""},"All",-1)),pe=["value"],fe={class:"flex flex-col"},me=l(()=>t("label",{for:"month",class:"font-medium mb-1"},"Choose a Month:",-1)),_e=l(()=>t("option",{value:""},"All",-1)),ve=["value"],he={class:"flex flex-col"},ge=l(()=>t("label",{for:"dayOfMonth",class:"font-medium mb-1"},"Choose a Day of the Month:",-1)),be=l(()=>t("option",{value:""},"All",-1)),ye=["value"],xe={class:"flex flex-col"},we=l(()=>t("label",{for:"dayOfWeek",class:"font-medium mb-1"},"Choose a Day of the Week:",-1)),Ce=l(()=>t("option",{value:""},"All",-1)),ke=["value"],Se={class:"flex flex-col"},$e=l(()=>t("label",{for:"hour",class:"font-medium mb-1"},"Choose an Hour:",-1)),De=l(()=>t("option",{value:""},"All",-1)),Me=["value"],Oe={class:"flex flex-col"},Ve=l(()=>t("label",{for:"offenseCodes",class:"font-medium mb-1"},"Enter Offense Codes (comma separated):",-1)),Ue={class:"flex flex-col"},Ae=l(()=>t("label",{for:"startDate",class:"font-medium mb-1"},"Start Date:",-1)),Le={class:"flex flex-col"},Ee=l(()=>t("label",{for:"endDate",class:"font-medium mb-1"},"End Date:",-1)),Fe={__name:"CrimeMapComponent",setup(v){const{props:k}=F(),h=u(null),g=u(null),m=u(k.crimeData||[]),s=u({offense_codes:"",offense_category:"",district:"",start_date:"",end_date:"",days_of_week:[],hours:[],day_of_month:"",month:"",year:"",ucr_part:"",location:"",shooting:""}),_=u("");axios.defaults.headers.common["X-CSRF-TOKEN"]=document.querySelector('meta[name="csrf-token"]').getAttribute("content");const $=u([...new Set(m.value.map(a=>a.offense_category))].sort()),D=u([...new Set(m.value.map(a=>a.district))].sort()),M=[2024,2023,2022,2021,2020,2019,2018,2017],O=u([...new Set(m.value.map(a=>a.month))].sort((a,o)=>a-o)),V=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],U=u([...new Set(m.value.map(a=>new Date(a.occurred_on_date).getDate()))].sort((a,o)=>a-o)),A=u([...new Set(m.value.map(a=>a.hour))].sort((a,o)=>a-o));T(()=>{h.value=w.map("map").setView([42.3601,-71.0589],13),w.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(h.value),g.value=w.markerClusterGroup(),h.value.addLayer(g.value),b()});const b=async()=>{try{const o=(await axios.post("/api/crime-data",{filters:s.value})).data;g.value.clearLayers(),o.crimeData.forEach(e=>{if(e.lat&&e.long){const C=`
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
          `,y=w.marker([e.lat,e.long]);y.bindPopup(C),g.value.addLayer(y)}})}catch(a){console.error("Failed to fetch crime data",a)}},L=a=>{const o=new Date(a),e=o.getFullYear(),C=String(o.getMonth()+1).padStart(2,"0"),y=String(o.getDate()).padStart(2,"0");return`${e}-${C}-${y}`},E=async()=>{try{const o=(await axios.post("/api/natural-language-query",{query:_.value})).data;m.value=o.crimeData,Object.keys(s.value).forEach(e=>{o.filters.hasOwnProperty(e)?e==="start_date"||e==="end_date"?s.value[e]=L(o.filters[e]):s.value[e]=o.filters[e]:s.value[e]=""}),b()}catch(a){console.error("Failed to process natural language query",a)}};return q(s,b,{deep:!0}),(a,o)=>(n(),r("div",Q,[R,H,K,j,t("div",z,[t("button",{onClick:o[0]||(o[0]=e=>_.value="All the fraud that happened last week"),class:"p-2 border rounded-md w-full mb-4"},"All the fraud that happened last week"),t("button",{onClick:o[1]||(o[1]=e=>_.value="Last month's welfare checks"),class:"p-2 border rounded-md w-full mb-4"},"Last month's welfare checks"),t("button",{onClick:o[2]||(o[2]=e=>_.value="Todo el robo que ocurrió el mes pasado"),class:"p-2 border rounded-md w-full mb-4"},"Todo el robo que ocurrió el mes pasado")]),d(t("input",{"onUpdate:modelValue":o[3]||(o[3]=e=>_.value=e),type:"text",placeholder:"Example: All the fraud that happened last week",class:"p-2 border rounded-md w-full mb-4"},null,512),[[x,_.value]]),t("button",{onClick:E,class:"p-2 bg-blue-500 text-white rounded-md mb-4"},"Submit to GPT-4o-mini"),s.value?(n(),r("pre",J,i(JSON.stringify(s.value,null,2)),1)):N("",!0),X,Z,t("div",ee,[t("div",te,[oe,d(t("select",{"onUpdate:modelValue":o[4]||(o[4]=e=>s.value.offense_category=e),class:"p-2 border rounded-md"},[se,(n(!0),r(p,null,f($.value,e=>(n(),r("option",{key:e,value:e},i(e),9,ae))),128))],512),[[c,s.value.offense_category]])]),t("div",le,[ne,d(t("select",{"onUpdate:modelValue":o[5]||(o[5]=e=>s.value.district=e),class:"p-2 border rounded-md"},[re,(n(!0),r(p,null,f(D.value,e=>(n(),r("option",{key:e,value:e},i(e),9,de))),128))],512),[[c,s.value.district]])]),t("div",ue,[ie,d(t("select",{"onUpdate:modelValue":o[6]||(o[6]=e=>s.value.year=e),class:"p-2 border rounded-md"},[ce,(n(),r(p,null,f(M,e=>t("option",{key:e,value:e},i(e),9,pe)),64))],512),[[c,s.value.year]])]),t("div",fe,[me,d(t("select",{"onUpdate:modelValue":o[7]||(o[7]=e=>s.value.month=e),class:"p-2 border rounded-md"},[_e,(n(!0),r(p,null,f(O.value,e=>(n(),r("option",{key:e,value:e},i(e),9,ve))),128))],512),[[c,s.value.month]])]),t("div",he,[ge,d(t("select",{"onUpdate:modelValue":o[8]||(o[8]=e=>s.value.day_of_month=e),class:"p-2 border rounded-md"},[be,(n(!0),r(p,null,f(U.value,e=>(n(),r("option",{key:e,value:e},i(e),9,ye))),128))],512),[[c,s.value.day_of_month]])]),t("div",xe,[we,d(t("select",{"onUpdate:modelValue":o[9]||(o[9]=e=>s.value.day_of_week=e),class:"p-2 border rounded-md"},[Ce,(n(),r(p,null,f(V,e=>t("option",{key:e,value:e},i(e),9,ke)),64))],512),[[c,s.value.day_of_week]])]),t("div",Se,[$e,d(t("select",{"onUpdate:modelValue":o[10]||(o[10]=e=>s.value.hour=e),class:"p-2 border rounded-md"},[De,(n(!0),r(p,null,f(A.value,e=>(n(),r("option",{key:e,value:e},i(e),9,Me))),128))],512),[[c,s.value.hour]])]),t("div",Oe,[Ve,d(t("input",{"onUpdate:modelValue":o[11]||(o[11]=e=>s.value.offense_codes=e),class:"p-2 border rounded-md",placeholder:"1103, 1104"},null,512),[[x,s.value.offense_codes]])]),t("div",Ue,[Ae,d(t("input",{type:"date","onUpdate:modelValue":o[12]||(o[12]=e=>s.value.start_date=e),class:"p-2 border rounded-md"},null,512),[[x,s.value.start_date]])]),t("div",Le,[Ee,d(t("input",{type:"date","onUpdate:modelValue":o[13]||(o[13]=e=>s.value.end_date=e),class:"p-2 border rounded-md"},null,512),[[x,s.value.end_date]])])]),t("button",{onClick:b,class:"mt-4 p-2 bg-blue-500 text-white rounded-md"},"Submit Filters")]))}},Te=S(Fe,[["__scopeId","data-v-54cefa39"]]);const qe={__name:"CrimeMap",setup(v){return(k,h)=>(n(),B(G,null,{default:W(()=>[Y(Te)]),_:1}))}},Qe=S(qe,[["__scopeId","data-v-4ee5e173"]]);export{Qe as default};
