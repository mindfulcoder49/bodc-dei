<script>
import { Head, Link } from '@inertiajs/vue3';

export default {
  name: 'PageTemplate',
  components: {
    Link,
    Head,
  },
};
</script>

<template>
    <!DOCTYPE html>
 <html lang="en">
   <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1" />
     <title>BODC-DEI</title>
   </head>
   <body>
     <header>
     <div class="smaller-square"><div class="larger-square"></div></div>
       <h1>BODC-DEI</h1>
       <nav>
         <ul>
            <li><Link :href="route('home')" class="btn btn-primary">Home</Link></li>
            <li><Link :href="route('about')" class="btn btn-primary">About</Link></li>
            <li><Link :href="route('projects')" class="btn btn-primary">Projects</Link></li>
            <li><Link :href="route('contact')" class="btn btn-primary">Contact</Link></li>
         </ul>
       </nav>
     </header>
     <slot></slot>
  
     <footer>
        <div class="larger-square"><div class="smaller-square"></div></div>
       <p>&copy; 2023 BODC-DEI</p>
     </footer>
   </body>
 </html>
 </template>
  

  