import{o as n,f as d,b as e,i as O,k as M,t as i,d as P,g as b,F as S,r as k,x as K,y as V,l as D,c as j,H as z,m,h as H,T as W,w as X,a as L,e as Z,n as $}from"./app-d8717654.js";import{B as ee,A as te}from"./AiAssistant-c2bbc47b.js";import{P as se}from"./PageTemplate-a66ebae3.js";import{_ as T}from"./_plugin-vue_export-helper-c27b6911.js";import"./leaflet-src-961d8190.js";import"./GuestLayout-1c44c54a.js";import"./ResponsiveNavLink-f4e51f3c.js";import"./AuthenticatedLayout-c40e29bb.js";const ae={name:"GenericDataList",props:{totalData:{type:Array,required:!0},itemsPerPage:{type:Number,default:10}},data(){return{currentPage:1,inputPage:1,sortKey:"date",sortOrder:"desc"}},computed:{totalPages(){return Math.ceil(this.totalData.length/this.itemsPerPage)},sortedData(){return[...this.totalData].sort((s,t)=>{let u=0;return s[this.sortKey]<t[this.sortKey]?u=-1:s[this.sortKey]>t[this.sortKey]&&(u=1),this.sortOrder==="asc"?u:-u})},paginatedData(){const s=(this.currentPage-1)*this.itemsPerPage,t=s+this.itemsPerPage;return this.sortedData.slice(s,t)}},watch:{currentPage(s){this.inputPage=s}},methods:{nextPage(){this.currentPage<this.totalPages&&this.currentPage++},prevPage(){this.currentPage>1&&this.currentPage--},goToPage(){this.inputPage>=1&&this.inputPage<=this.totalPages?this.currentPage=this.inputPage:this.inputPage=this.currentPage},sortBy(s){this.sortKey===s?this.sortOrder=this.sortOrder==="asc"?"desc":"asc":(this.sortKey=s,this.sortOrder="desc")}}},Q=s=>(K("data-v-95caed61"),s=s(),V(),s),oe={class:"flex justify-between items-center mt-4 mb-4"},re=["disabled"],ne={class:"flex items-center"},le=Q(()=>e("span",{class:"mr-2"},"Page",-1)),ie=["max"],de={class:"ml-2"},ce=["disabled"],ue={key:0,class:"text-center text-gray-500"},ge={key:1,class:"overflow-x-auto"},_e={class:"text-center text-gray-500 mb-4"},pe={class:"table-auto w-full border-collapse border border-gray-200"},he={class:"bg-gray-100"},me={key:0},ye={key:0},be={key:0},fe={key:0},xe=Q(()=>e("th",{class:"px-4 py-2 border border-gray-300"},"Info",-1)),ve={class:"border px-4 py-2 text-center"},we={class:"border px-4 py-2 text-center"},Pe={class:"border px-4 py-2 text-center"},De={class:"border px-4 py-2 text-center"},Ce={class:"border px-4 py-2"},Se={class:"max-h-32 overflow-y-auto"},ke={class:"font-semibold"};function Ae(s,t,u,a,l,r){return n(),d("div",null,[e("div",oe,[e("button",{onClick:t[0]||(t[0]=(...o)=>r.prevPage&&r.prevPage(...o)),disabled:l.currentPage===1,class:"p-2 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 w-1/4 sm:w-1/6 disabled:bg-gray-300"}," Previous ",8,re),e("div",ne,[le,O(e("input",{"onUpdate:modelValue":t[1]||(t[1]=o=>l.inputPage=o),onChange:t[2]||(t[2]=(...o)=>r.goToPage&&r.goToPage(...o)),type:"number",min:"1",max:r.totalPages,class:"w-16 p-1 border rounded-md text-center"},null,40,ie),[[M,l.inputPage,void 0,{number:!0}]]),e("span",de,"of "+i(r.totalPages),1)]),e("button",{onClick:t[3]||(t[3]=(...o)=>r.nextPage&&r.nextPage(...o)),disabled:l.currentPage===r.totalPages,class:"p-2 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 w-1/4 sm:w-1/6 disabled:bg-gray-300"}," Next ",8,ce)]),r.paginatedData.length===0?(n(),d("div",ue," No results found ")):(n(),d("div",ge,[e("div",_e," Number of results: "+i(u.totalData.length),1),e("table",pe,[e("thead",he,[e("tr",null,[e("th",{onClick:t[4]||(t[4]=o=>r.sortBy("latitude")),class:"px-4 py-2 border border-gray-300 cursor-pointer"},[P(" Latitude "),l.sortKey==="latitude"?(n(),d("span",me,i(l.sortOrder==="desc"?"↓":"↑"),1)):b("",!0)]),e("th",{onClick:t[5]||(t[5]=o=>r.sortBy("longitude")),class:"px-4 py-2 border border-gray-300 cursor-pointer"},[P(" Longitude "),l.sortKey==="longitude"?(n(),d("span",ye,i(l.sortOrder==="desc"?"↓":"↑"),1)):b("",!0)]),e("th",{onClick:t[6]||(t[6]=o=>r.sortBy("date")),class:"px-4 py-2 border border-gray-300 cursor-pointer"},[P(" Date "),l.sortKey==="date"?(n(),d("span",be,i(l.sortOrder==="desc"?"↓":"↑"),1)):b("",!0)]),e("th",{onClick:t[7]||(t[7]=o=>r.sortBy("type")),class:"px-4 py-2 border border-gray-300 cursor-pointer"},[P(" Type "),l.sortKey==="type"?(n(),d("span",fe,i(l.sortOrder==="desc"?"↓":"↑"),1)):b("",!0)]),xe])]),e("tbody",null,[(n(!0),d(S,null,k(r.paginatedData,(o,_)=>(n(),d("tr",{key:_},[e("td",ve,i(o.latitude),1),e("td",we,i(o.longitude),1),e("td",Pe,i(new Date(o.date).toLocaleString()),1),e("td",De,i(o.type),1),e("td",Ce,[e("ul",Se,[(n(!0),d(S,null,k(o.info,(p,y)=>(n(),d("li",{key:y},[e("span",ke,i(y)+":",1),P(" "+i(p),1)]))),128))])])]))),128))])])]))])}const Oe=T(ae,[["render",Ae],["__scopeId","data-v-95caed61"]]);const Me={name:"JsonTree",props:{json:{type:Object,required:!0}},data(){return{collapsed:{}}},methods:{isObjectOrArray(s){return typeof s=="object"&&s!==null},formatValue(s){return typeof s=="string"?`"${s}"`:s},toggleCollapse(s){this.collapsed[s]=!this.collapsed[s]}}},Te={class:"json-tree"},Ne={class:"list-none pl-5"},Le=["onClick"],je={key:0,class:"text-blue-500"},Ie={key:1,class:"text-gray-700"},Be={key:0,class:"ml-5"};function Fe(s,t,u,a,l,r){const o=D("JsonTree",!0);return n(),d("div",Te,[e("ul",Ne,[(n(!0),d(S,null,k(u.json,(_,p)=>(n(),d("li",{key:p,class:""},[e("span",{onClick:y=>r.toggleCollapse(p),class:"cursor-pointer font-semibold"},[P(i(p)+": ",1),r.isObjectOrArray(_)?(n(),d("span",je," ["+i(l.collapsed[p]?"...":Array.isArray(_)?"Array":"Object")+"] ",1)):(n(),d("span",Ie,i(r.formatValue(_)),1))],8,Le),!l.collapsed[p]&&r.isObjectOrArray(_)?(n(),d("div",Be,[r.isObjectOrArray(_)?(n(),j(o,{key:0,json:_},null,8,["json"])):b("",!0)])):b("",!0)]))),128))])])}const Ke=T(Me,[["render",Fe],["__scopeId","data-v-0ca97707"]]);const Ve={props:["initialSearchQuery"],data(){return{searchQuery:this.initialSearchQuery||"",results:[]}},methods:{async searchAddresses(){if(this.searchQuery.length>=3)try{const s=await z.get("/search-address",{params:{address:this.searchQuery}});this.results=s.data.data}catch(s){console.error("Error fetching address data:",s)}else this.results=[]},selectAddress(s){const t={lat:s.y_coord,lng:s.x_coord};this.$emit("address-selected",t)}}},Qe={key:0,class:"mt-2 border rounded p-2 bg-white shadow"},Ge=["onClick"],Je={key:1,class:"text-gray-500 mt-2"};function Ue(s,t,u,a,l,r){return n(),d("div",null,[O(e("input",{type:"text","onUpdate:modelValue":t[0]||(t[0]=o=>l.searchQuery=o),onInput:t[1]||(t[1]=(...o)=>r.searchAddresses&&r.searchAddresses(...o)),placeholder:"Enter address",class:"border p-2 rounded w-full"},null,544),[[M,l.searchQuery]]),l.results.length?(n(),d("ul",Qe,[(n(!0),d(S,null,k(l.results,(o,_)=>(n(),d("li",{key:_,onClick:p=>r.selectAddress(o),class:"cursor-pointer hover:bg-gray-100 p-2 rounded"},i(o.full_address)+" - ("+i(o.x_coord)+", "+i(o.y_coord)+") ",9,Ge))),128))])):b("",!0),!l.results.length&&l.searchQuery?(n(),d("p",Je,"No results found.")):b("",!0)])}const Ye=T(Ve,[["render",Ue],["__scopeId","data-v-70f9f357"]]);const Ee={name:"RadialMap",components:{BostonMap:ee,PageTemplate:se,AiAssistant:te,GenericDataList:Oe,JsonTree:Ke,AddressSearch:Ye},props:["dataPoints","centralLocation"],setup(s){const t=m({}),u=m(!1),a=m(!1),l=m(null),r=m(null),o=m([42.3601,-71.0589]),_=m(!1),p=m(!0),y=m(""),f=m(""),g=m(""),x=m(0),N=m(!0),I=m(0);if(s.dataPoints.length>0){const c=s.dataPoints.map(v=>new Date(v.date));f.value=c.reduce((v,C)=>v<C?v:C).toISOString().split("T")[0],g.value=c.reduce((v,C)=>v>C?v:C).toISOString().split("T")[0],y.value=f.value;const h=new Date(f.value),q=new Date(g.value);I.value=Math.ceil((q-h)/(1e3*60*60*24))}const G=()=>{const c=new Date(f.value),h=new Date(c.getTime()+x.value*(1e3*60*60*24));y.value=h.toISOString().split("T")[0]},J=()=>{const c=new Date(f.value),h=new Date(y.value);x.value=Math.floor((h-c)/(1e3*60*60*24))},U=H(()=>N.value?s.dataPoints.filter(h=>t.value[h.type]):s.dataPoints.filter(h=>new Date(h.date).toISOString().split("T")[0]===y.value).filter(h=>t.value[h.type])),A=W({centralLocation:{latitude:s.centralLocation.latitude,longitude:s.centralLocation.longitude}}),Y=()=>{p.value=!1,setTimeout(()=>{p.value=!0},0)};s.dataPoints.forEach(c=>{t.value[c.type]||(t.value[c.type]=!0)});const E=c=>{t.value[c]=!t.value[c]},R=()=>{u.value?(u.value=!1,a.value=!1,l.value=null,_.value=!0,r.value&&(r.value=null)):(u.value=!0,a.value=!1,l.value=null,_.value=!1)},B=c=>{u.value&&(l.value=c,a.value=!0,A.centralLocation.latitude=c.lat,A.centralLocation.longitude=c.lng,r.value=c,o.value=[c.lat,c.lng])},F=()=>{A.post("/map",{preserveScroll:!0,onSuccess:()=>{u.value=!1,a.value=!1}})};return{filters:t,filteredDataPoints:U,toggleFilter:E,toggleCenterSelection:R,setNewCenter:B,submitNewCenter:F,centerSelectionActive:u,centerSelected:a,newCenter:l,form:A,mapCenter:o,cancelNewMarker:_,newMarker:r,showMap:p,reloadMap:Y,selectedDate:y,minDate:f,maxDate:g,daysBetweenMinAndMax:I,updateDateFromSlider:G,updateSliderFromInput:J,dayOffset:x,showAllDates:N,updateCenterCoordinates:c=>{const h={lat:c.lat,lng:c.lng};console.log("Updating center coordinates:",h),u.value=!0,B(h),F()}}}},w=s=>(K("data-v-413d6318"),s=s(),V(),s),Re=w(()=>e("h1",{class:"text-2xl font-bold text-gray-800 text-center"},"The Boston App",-1)),qe=w(()=>e("p",{class:"text-gray-700 mt-4 mt-8 text-lg leading-relaxed text-center"},' This map displays crime, 311 cases, and building permits located within a half mile from the center point. You can choose a new center point by clicking the "Choose New Center" button and then clicking on the map. Click "Save New Center" to update the map. ',-1)),ze={key:0,class:"p-4 bg-gray-100 rounded-lg shadow text-center"},He=w(()=>e("p",{class:"font-bold text-gray-800"},"Selected Center Coordinates:",-1)),We={class:"text-gray-700"},Xe={key:1,class:"p-4 bg-gray-100 rounded-lg shadow text-center"},Ze=w(()=>e("p",{class:"font-bold text-gray-800"},"Current Center Coordinates:",-1)),$e={class:"text-gray-700"},et={class:"flex space-x-4"},tt=["disabled"],st={class:"date-filter-container mt-4 mb-4 flex flex-col w-full"},at={class:"flex items-center w-full space-x-4"},ot=w(()=>e("label",{for:"date-range",class:"text-m font-bold w-1/5"},"Filter by Date:",-1)),rt={class:"w-1/5"},nt={class:"text-sm p-2 text-left"},lt=["max","disabled"],it={class:"w-1/5"},dt={class:"text-sm p-2 text-right"},ct={class:"flex justify-between items-center w-full mt-4 min-[400px]:flex-row flex-col"},ut=w(()=>e("div",{class:"flex items-center space-x-1 min-[400px]:w-1/3 w-full min-[400px]:justify-end pr-2"},[e("p",{class:"text-sm text-center w-full min-[400px]:text-right"},"Selected:")],-1)),gt={class:"min-[400px]:w-1/3 w-full"},_t=["disabled"],pt={class:"min-[400px]:w-1/3 w-full"},ht={class:"filter-container flex sm:space-x-4 space-x-0"},mt=["onClick"],yt={class:"invisible md:visible"},bt=w(()=>e("p",{class:"text-gray-700 mt-4 mb-4 text-lg leading-relaxed"}," Filter by data type by clicking the filter buttons above ",-1));function ft(s,t,u,a,l,r){const o=D("AddressSearch"),_=D("BostonMap"),p=D("AiAssistant"),y=D("GenericDataList"),f=D("PageTemplate");return n(),j(f,null,{default:X(()=>[Re,qe,L(o,{onAddressSelected:a.updateCenterCoordinates},null,8,["onAddressSelected"]),e("form",{onSubmit:t[1]||(t[1]=Z((...g)=>a.submitNewCenter&&a.submitNewCenter(...g),["prevent"])),class:"space-y-4 mb-4"},[a.newCenter?(n(),d("div",ze,[He,e("p",We,i(a.newCenter.lat)+", "+i(a.newCenter.lng),1)])):(n(),d("div",Xe,[Ze,e("p",$e,i(u.centralLocation.latitude)+", "+i(u.centralLocation.longitude),1)])),e("div",et,[e("button",{type:"button",onClick:t[0]||(t[0]=(...g)=>a.toggleCenterSelection&&a.toggleCenterSelection(...g)),class:"px-4 py-2 text-white bg-blue-500 rounded-lg shadow-lg disabled:bg-gray-400 hover:bg-blue-600 transition-colors w-1/2"},i(a.centerSelectionActive?"Cancel":"Choose New Center"),1),e("button",{type:"submit",disabled:!a.centerSelected,class:"px-4 py-2 text-white bg-green-500 rounded-lg disabled:bg-gray-400 hover:bg-green-600 transition-colors w-1/2"}," Save New Center ",8,tt)])],32),a.showMap?(n(),j(_,{key:0,dataPoints:a.filteredDataPoints,center:a.mapCenter,centralLocation:u.centralLocation,centerSelectionActive:a.centerSelectionActive,cancelNewMarker:a.cancelNewMarker,onMapClick:a.setNewCenter},null,8,["dataPoints","center","centralLocation","centerSelectionActive","cancelNewMarker","onMapClick"])):b("",!0),e("div",null,[e("div",st,[e("div",at,[ot,e("div",rt,[e("p",nt,i(a.minDate),1)]),O(e("input",{id:"date-range",type:"range",min:0,max:a.daysBetweenMinAndMax,"onUpdate:modelValue":t[2]||(t[2]=g=>a.dayOffset=g),disabled:a.showAllDates,onInput:t[3]||(t[3]=(...g)=>a.updateDateFromSlider&&a.updateDateFromSlider(...g)),class:"w-4/5"},null,40,lt),[[M,a.dayOffset]]),e("div",it,[e("p",dt,i(a.maxDate),1)])]),e("div",ct,[ut,e("div",gt,[O(e("input",{type:"date","onUpdate:modelValue":t[4]||(t[4]=g=>a.selectedDate=g),disabled:a.showAllDates,onChange:t[5]||(t[5]=(...g)=>a.updateSliderFromInput&&a.updateSliderFromInput(...g)),class:"border rounded-lg w-full min-[400px]:max-w-[200px] p-2",placeholder:"YYYY-MM-DD"},null,40,_t),[[M,a.selectedDate]])]),e("div",pt,[e("button",{onClick:t[6]||(t[6]=g=>a.showAllDates=!a.showAllDates),class:"px-4 py-2 text-white bg-blue-500 rounded-lg shadow-lg hover:bg-blue-600 transition-colors w-full"},i(a.showAllDates?"Filter by Date":"Show All Dates"),1)])])]),e("div",ht,[(n(!0),d(S,null,k(a.filters,(g,x)=>(n(),d("button",{key:x,onClick:N=>a.toggleFilter(x),class:$([{active:g,inactive:!g,[`${x.toLowerCase().replace(" ","-").replace(/\d/g,"a")}-filter-button`]:!0},"filter-button px-2 py-2 rounded-lg shadow-lg disabled:bg-gray-400 transition-colors w-1/4 text-base"])},[e("span",yt,i(x),1)],10,mt))),128)),e("button",{onClick:t[7]||(t[7]=(...g)=>a.reloadMap&&a.reloadMap(...g)),class:"px-4 py-2 text-white bg-red-500 rounded-lg shadow-lg hover:bg-red-600 transition-colors w-1/4"}," Reload Map ")]),bt,L(p,{context:a.filteredDataPoints},null,8,["context"]),L(y,{totalData:a.filteredDataPoints,itemsPerPage:5},null,8,["totalData"])])]),_:1})}const Ot=T(Ee,[["render",ft],["__scopeId","data-v-413d6318"]]);export{Ot as default};