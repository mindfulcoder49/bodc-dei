import{T as m,o as l,c as d,w as t,b as a,u as o,Z as c,a as e,d as p,n as u,e as f}from"./app-d69f466a.js";import{_}from"./GuestLayout-a83fbb27.js";import{_ as w,a as b,b as h}from"./TextInput-17b18f88.js";import{P as x}from"./PrimaryButton-047823bd.js";import"./ApplicationLogo-9852be7e.js";import"./_plugin-vue_export-helper-c27b6911.js";const g=e("div",{class:"mb-4 text-sm text-gray-600"}," This is a secure area of the application. Please confirm your password before continuing. ",-1),y=["onSubmit"],P={class:"flex justify-end mt-4"},S={__name:"ConfirmPassword",setup(V){const s=m({password:""}),i=()=>{s.post(route("password.confirm"),{onFinish:()=>s.reset()})};return(v,r)=>(l(),d(_,null,{default:t(()=>[a(o(c),{title:"Confirm Password"}),g,e("form",{onSubmit:f(i,["prevent"])},[e("div",null,[a(w,{for:"password",value:"Password"}),a(b,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:o(s).password,"onUpdate:modelValue":r[0]||(r[0]=n=>o(s).password=n),required:"",autocomplete:"current-password",autofocus:""},null,8,["modelValue"]),a(h,{class:"mt-2",message:o(s).errors.password},null,8,["message"])]),e("div",P,[a(x,{class:u(["ml-4",{"opacity-25":o(s).processing}]),disabled:o(s).processing},{default:t(()=>[p(" Confirm ")]),_:1},8,["class","disabled"])])],40,y)]),_:1}))}};export{S as default};
