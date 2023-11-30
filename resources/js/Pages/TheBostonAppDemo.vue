
<template>
  <PageTemplate>
    <Head>
      <title>The Boston App Demo</title>
    </Head>
    <section>
      <h2>The Boston App Demo</h2>
      <p>
          The Boston App uses GPT3 to generate personalized location based reports of city activities. The idea is for the user to input an address, a radius, a timeframe, and city services of interest, and receive a personalized natural language report in any language with the information they need.
      </p>
    </section>
    <section>
      <h2>Example: English, Park Plaza Hotel, .25 km radius, Nov 20 - Nov 30</h2>
      <p>
        <ol>
        <li>
            <strong>Approved Building Permits:</strong>
            <ul>
                <li>Location: 201-241 Stuart St, Boston, MA 2116</li>
                <li>Time and Date: November 20, 2023, at 18:28:17 UTC</li>
                <li>Description: Replacement of a 5 Ton water source heat pump above the meat cooler in the kitchen.</li>
            </ul>
        </li>
        <li>
            <strong>Approved Building Permits:</strong>
            <ul>
                <li>Location: 201-241 Stuart St, Boston, MA 2116</li>
                <li>Time and Date: November 20, 2023, at 18:17:55 UTC</li>
                <li>Description: Replacement of a 10 Ton Water source Heat Pump for the server dining room above the kitchen ceiling.</li>
            </ul>
        </li>
        <li>
            <strong>Approved Building Permits:</strong>
            <ul>
                <li>Location: 27-29 Isabella St, Boston, MA 2116</li>
                <li>Time and Date: November 22, 2023, at 18:23:13 UTC</li>
                <li>Description: Installation of a car charger for Apartment #8, Space #3.</li>
            </ul>
        </li>
        <li>
            <strong>Approved Building Permits:</strong>
            <ul>
                <li>Location: 340-360 Boylston St, Boston, MA 2116</li>
                <li>Time and Date: November 29, 2023, at 18:32:48 UTC</li>
                <li>Description: Core & Shell service, lighting & power.</li>
            </ul>
        </li>
        <li>
            <strong>Electrical Low Voltage Permit:</strong>
            <ul>
                <li>Location: 10-54 Park Pz, Boston, MA 2116</li>
                <li>Time and Date: November 28, 2023, at 13:09:58 UTC</li>
                <li>Description: Installation of a low voltage automation system at DOT2201 State Transport Building.</li>
            </ul>
        </li>
        <li>
            <strong>Electrical Low Voltage Permit:</strong>
            <ul>
                <li>Location: 50 Park PZ, Boston, MA 2116</li>
                <li>Time and Date: November 21, 2023, at 18:04:23 UTC</li>
                <li>Description: New Cat6 data cabling for 12 office/open area locations, 2 WAP locations, and 1 server cabinet location.</li>
            </ul>
        </li>
        <li>
            <strong>Excavation Permit:</strong>
            <ul>
                <li>Location: 52 Providence St, Boston, MA 2116</li>
                <li>Time and Date: November 20, 2023, at 16:37:34 UTC</li>
                <li>Description: New construction at 350 Boylston St, including utility connections and excavation for electrical subcontractor.</li>
            </ul>
        </li>
        <li>
            <strong>Excavation Permit:</strong>
            <ul>
                <li>Location: 0 Berkeley St, Boston, MA 2116</li>
                <li>Time and Date: November 22, 2023, at 14:22:25 UTC</li>
                <li>Description: Relay gas main at Berkeley St @ Beacon St.</li>
            </ul>
        </li>
        <li>
            <strong>Short Form Building Permit:</strong>
            <ul>
                <li>Location: 75 Arlington St, Boston, MA 2116</li>
                <li>Time and Date: November 21, 2023, at 16:40:10 UTC</li>
                <li>Description: Minor works for new security walls, doors, painting & LVT flooring installation.</li>
            </ul>
        </li>
        <li>
            <strong>Short Form Building Permit:</strong>
            <ul>
                <li>Location: 26 Melrose St, Boston, MA 2116</li>
                <li>Time and Date: November 27, 2023, at 15:33:59 UTC</li>
                <li>Description: Repointing rear facade, restoring brownstone sills and lintels, painting fire escapes.</li>
            </ul>
        </li>
        <li>
            <strong>Moving Truck Permits:</strong>
            <ul>
                <li>Location: Stand at curb, Boston, MA 2116</li>
                <li>Time and Date: November 22, 2023, to November 28, 2023</li>
                <li>Description: Permit for moving trucks to occupy street space.</li>
            </ul>
        </li>
        <li>
            <strong>Moving Truck Permits:</strong>
            <ul>
                <li>Location: Stand at curb, Boston, MA 2116</li>
                <li>Time and Date: November 24, 2023, ...</li> <!-- It seems the date is incomplete -->
                <!-- Add the rest of the description here if available -->
            </ul>
        </li>
    </ol>
      </p>
    </section>
    <section>
      <h2>Example: Spanish, Park Plaza Hotel, .25 km radius, Nov 20 - Nov 29</h2>
      <p>
        <ol>
        <li>
            <strong>Permiso de construcción aprobado (ID de conjunto de datos: approved-building-permits):</strong>
            <ul>
                <li>Número de permiso: E1546596</li>
                <li>Tipo de trabajo: Eléctrico</li>
                <li>Descripción: Reemplazo de una bomba de calor fuente de agua de 5 toneladas sobre el refrigerador de carne en la cocina.</li>
                <li>Solicitante: Edward DiMarzio</li>
                <li>Valor declarado: $2,000.00</li>
                <li>Fecha de emisión: 20 de noviembre de 2023</li>
                <li>Fecha de vencimiento: 20 de mayo de 2024</li>
                <li>Estado: Abierto</li>
            </ul>
        </li>
        <li>
            <strong>Permiso de construcción aprobado (ID de conjunto de datos: approved-building-permits):</strong>
            <ul>
                <li>Número de permiso: E1546601</li>
                <li>Tipo de trabajo: Eléctrico</li>
                <li>Descripción: Reemplazo de una bomba de calor fuente de agua de 10 toneladas para el comedor del servidor sobre el techo de la cocina.</li>
                <li>Solicitante: Edward DiMarzio</li>
                <li>Valor declarado: $3,000.00</li>
                <li>Fecha de emisión: 20 de noviembre de 2023</li>
                <li>Fecha de vencimiento: 20 de mayo de 2024</li>
                <li>Estado: Abierto</li>
            </ul>
        </li>
        <li>
            <strong>Permiso de construcción aprobado (ID de conjunto de datos: approved-building-permits):</strong>
            <ul>
                <li>Número de permiso: E1546687</li>
                <li>Tipo de trabajo: Eléctrico</li>
                <li>Descripción: Instalación de cargador de automóvil en el Apartamento #8, Espacio #3.</li>
                <li>Solicitante: Raymond Daly</li>
                <li>Valor declarado: $3,800.00</li>
                <li>Fecha de emisión: 22 de noviembre de 2023</li>
                <li>Fecha de vencimiento: 22 de mayo de 2024</li>
                <li>Estado: Abierto</li>
            </ul>
        </li>
        <li>
            <strong>Permiso de construcción aprobado (ID de conjunto de datos: approved-building-permits):</strong>
            <ul>
                <li>Número de permiso: ELV1546090</li>
                <li>Tipo de trabajo: Eléctrico, Baja tensión</li>
                <li>Descripción: Instalación de un sistema de automatización de baja tensión en el edificio de transporte del Departamento de Transporte del Estado.</li>
                <li>Solicitante: Paul McWhinnie</li>
                <li>Valor declarado: $1,142,400.00</li>
                <li>Fecha de emisión: 28 de noviembre de 2023</li>
                <li>Fecha de vencimiento: 28 de mayo de 2024</li>
                <li>Estado: Abierto</li>
            </ul>
        </li>
        <li>
            <strong>Permiso de construcción aprobado (ID de conjunto de datos: approved-building-permits):</strong>
            <ul>
                <li>Número de permiso: ELV1546909</li>
                <li>Tipo de trabajo: Eléctrico, Baja tensión</li>
                <li>Descripción: Instalación de cableado de datos Cat6 en la Suite 614 de Park Plaza, para 30 puertos en 12 ubicaciones de oficinas/áreas abiertas, 2 ubicaciones de puntos de acceso inalámbrico y 1 ubicación de armario de servidores.</li>
                <li>Solicitante: Doug Lynch</li>
                <li>Valor declarado: $6,897.00</li>
                <li>Fecha de emisión: 21 de noviembre de 2023</li>
                <li>Fecha de vencimiento: 21 de mayo de 2024</li>
                <li>Estado: Abierto</li>
            </ul>
        </li>
        <li>
            <strong>Permiso de excavación (ID de conjunto de datos: moving-truck-permits):</strong>
            <ul>
                <li>Número de permiso: EXCA-1488875</li>
                <li>Tipo de trabajo: Servicio de Excavación</li>
                <li>Descripción: Mantenimiento de la seguridad peatonal, instalación de nueva drenaje en la carretera, excavación para el subcontratista eléctrico.</li>
                <li>Solicitante: NaN</li>
                <li>Valor declarado: $0.00</li>
                <li>Fecha de emisión: 20 de noviembre de 2023</li>
                <li>Fecha de vencimiento: 15 de diciembre de 2023</li>
                <li>Estado: Abierto</li>
            </ul>
        </li>
        <li>
            <strong>Permiso de excavación (ID de conjunto de datos: moving-truck-permits):</strong>
            <ul>
                <li>Número de permiso: EXCA-1519830</li>
                <li>Tipo de trabajo: Servicio de Excavación</li>
                <li>Descripción: ...</li> <!-- Complete the rest of the details -->
            </ul>
        </li>
        <!-- Include additional list items here -->
    </ol>
      </p>
    </section>
    <section>
    <iframe 
      :src="notebookUrl" 
      style="width:100%; height:80vh; border:none;"
    ></iframe>
  </section>
  </PageTemplate>
</template>

<script>
import PageTemplate from '../Components/PageTemplate.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
  props: {
    cases: {
      type: Array,
      required: true,
    },
    search: {
      type: String,
      required: false,
    },
  },
  components: {
    PageTemplate, Head, Link
  },
  data() {
    return {
      // URL to the HTML file
      notebookUrl: '/The_Boston_App.html'
    };
  },
  computed: {
  },
  methods: {
  },
};
</script>
