<?php

namespace App\Http\Controllers;

use App\Models\CrimeData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;

class CrimeMapController extends Controller
{

    public function index(Request $request)
    {
        $crimeData = CrimeData::limit(1500)->get();

        return Inertia::render('CrimeMap', [
            'crimeData' => $crimeData,
            'filters' => $request->all(),
        ]);
    }

    public function getCrimeData(Request $request)
    {
        $query = CrimeData::query();

        $filters = $request['filters'];
        
        if (isset($filters['offense_codes']) && is_array($filters['offense_codes']) && !empty($filters['offense_codes'])) {
            $query->whereIn('offense_code', $filters['offense_codes']);
        }
        
        if (isset($filters['offense_category']) && !empty($filters['offense_category'])) {
            $query->where('offense_category', $filters['offense_category']);
        }
        
        if (isset($filters['district']) && !empty($filters['district'])) {
            if (strpos($filters['district'], '%') !== false) {
                $query->where('district', 'like', $filters['district']);
            } else {
                $query->where('district', $filters['district']);
            }
        }
        
        if (isset($filters['start_date']) && isset($filters['end_date']) && !empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('occurred_on_date', [$filters['start_date'], $filters['end_date']]);
        }
        
        if (isset($filters['days_of_week']) && is_array($filters['days_of_week']) && !empty($filters['days_of_week'])) {
            $query->whereIn('day_of_week', $filters['days_of_week']);
        }
        
        if (isset($filters['hours']) && is_array($filters['hours']) && !empty($filters['hours'])) {
            $query->whereIn('hour', $filters['hours']);
        }

        //year 
        if (isset($filters['year']) && !empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }
        
        if (isset($filters['ucr_part']) && !empty($filters['ucr_part'])) {
            $query->where('ucr_part', $filters['ucr_part']);
        }
        
        if (isset($filters['location']) && !empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }
        
        if (isset($filters['shooting']) && !empty($filters['shooting'])) {
            $query->where('shooting', $filters['shooting']);
        }


        $query->limit(1500);
        $crimeData = $query->get();

        //return crimedata  and filters to the front end
        return response()->json(['crimeData' => $crimeData, 'filters' => $filters, 'applied_filters' => $query->toSql()]);
    }

    public function naturalLanguageQuery(Request $request)
    {
        $queryText = $request->input('query');
        $gptResponse = $this->queryGPT($queryText);

        $gptResponse = json_decode($gptResponse, true);

        if (isset($gptResponse['filters'])) {
            return $this->getCrimeData(Request::create('/api/crime-data', 'POST', $gptResponse));
        }

        return response()->json(['error' => 'Could not parse query', 
                                 'query' => $queryText,
                                    'response' => $gptResponse], 400);
    }

    private function queryGPT($queryText)
    {
        //return some fake data for now
        //return '{ "filters": { "year": 2023 } }';

        $client = new Client();
        $apiKey = config('services.openai.api_key');

        $description = 'offense_codes: Array of integers (examples: [3115, 3301, 423]).
        offense_category: String describing the offense category (examples: "INVESTIGATE PERSON", "ASSAULT - AGGRAVATED").
        district: String representing a district code (examples: "B3", "A1", "D4").
        start_date: String in "yyyy-MM-dd" format (example: "2023-01-27").
        end_date: String in "yyyy-MM-dd" format (example: "2023-02-15").
        days_of_week: Array of strings representing days of the week (examples: ["Monday", "Tuesday", "Friday"]).
        hours: Array of integers from 0 to 23 (examples: [14, 22, 6]).
        ucr_part: String or empty (example: "").
        location: String representing part of a street name (examples: "FAVRE ST", "HAROLD ST").
        shooting: Boolean (true or false).';

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => 'The current datetime is' . date('Y-m-d H:i:s')],
                    ['role' => 'user', 'content' => "Convert this query into crime data filters: {$queryText}"],
                    ['role' => 'user', 'content' => "Here are the offense codes and names: \n" . $this->getCrimeDataContext()],
                ],
                'functions' => [
                    [
                        'name' => 'filter_crime_data',
                        'description' => 'Generate filters for crime data query: ' . $description,
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'filters' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'offense_codes' => ['type' => 'array', 'items' => ['type' => 'integer']],
                                        'offense_category' => ['type' => 'string'],
                                        'district' => ['type' => 'string'],
                                        'start_date' => ['type' => 'string', 'format' => 'date-time'],
                                        'end_date' => ['type' => 'string', 'format' => 'date-time'],
                                        'days_of_week' => ['type' => 'array', 'items' => ['type' => 'string']],
                                        'hours' => ['type' => 'array', 'items' => ['type' => 'integer']],
                                        'ucr_part' => ['type' => 'string'],
                                        'location' => ['type' => 'string'],
                                        'shooting' => ['type' => 'boolean'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'function_call' => 'auto',
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        $filters = $responseBody['choices'][0]['message']['function_call']['arguments'];

        return $filters;
    }

    //add a function to get crime data context, which is a lot of text to include in the prompt

    private function getCrimeDataContext()
    {
        $crimeContext = <<<EOT
                CODE	NAME
        612	LARCENY PURSE SNATCH - NO FORCE 
        613	LARCENY SHOPLIFTING
        615	LARCENY THEFT OF MV PARTS & ACCESSORIES
        1731	INCEST
        3111	LICENSE PREMISE VIOLATION
        2646	LIQUOR - DRINKING IN PUBLIC
        2204	LIQUOR LAW VIOLATION
        3810	M/V ACCIDENT - INVOLVING  BICYCLE - INJURY
        3801	M/V ACCIDENT - OTHER
        3807	M/V ACCIDENT - OTHER CITY VEHICLE
        3803	M/V ACCIDENT - PERSONAL INJURY
        3805	M/V ACCIDENT - POLICE VEHICLE
        3802	M/V ACCIDENT - PROPERTY  DAMAGE
        3205	M/V PLATES - LOST
        123	MANSLAUGHTER - NON-VEHICLE - NEGLIGENCE
        121	MANSLAUGHTER - VEHICLE - NEGLIGENCE
        3501	MISSING PERSON
        3502	MISSING PERSON - LOCATED
        3503	MISSING PERSON - NOT REPORTED - LOCATED
        111	MURDER, NON-NEGLIGIENT MANSLAUGHTER
        3303	NOISY PARTY/RADIO-ARREST
        2623	OBSCENE MATERIALS - PORNOGRAPHY
        2628	OBSCENE PHONE CALLS
        1711	OPEN & GROSS LEWDNESS
        2007	VIOL. OF RESTRAINING ORDER W NO ARREST
        2102	OPERATING UNDER THE INFLUENCE DRUGS
        2660	OTHER OFFENSE
        2900	VAL - VIOLATION OF AUTO LAW - OTHER
        1109	FRAUD - WIRE
        2616	POSSESSION OF BURGLARIOUS TOOLS
        2606	PRISONER ATTEMPT TO RESCUE
        2636	PRISONER ESCAPE / ESCAPE & RECAPTURE
        3029	PRISONER - SUICIDE / SUICIDE ATTEMPT
        3106	PROPERTY - ACCIDENTAL DAMAGE
        1300	STOLEN PROPERTY - BUYING / RECEIVING / POSSESSING
        3207	PROPERTY - FOUND
        3201	PROPERTY - LOST
        3202	PROPERTY - LOST THEN LOCATED
        3208	PROPERTY - MISSING
        1304	PROPERTY - STOLEN THEN RECOVERED
        1603	PROSTITUTION - ASSISTING OR PROMOTING
        1605	PROSTITUTION - COMMON NIGHTWALKER
        1601	PROSTITUTION
        1602	PROSTITUTION - SOLICITING
        242	RAPE - ATTEMPT - SODOMY
        254	RAPE - COMPLETE - FONDLING
        271	RAPE - COMPLETE - OTHER
        244	RAPE - ATTEMPT - FONDLING
        251	RAPE - COMPLETE - FORCIBLE
        241	RAPE - ATTEMPT - FORCIBLE
        243	RAPE - ATTEMPT - SEXUAL ASSAULT W/ OBJECT
        261	RAPE - ATTEMPT - OTHER
        252	RAPE - COMPLETE - SODOMY
        253	RAPE - COMPLETE - SEXUAL ASSAULT W/ OBJECT
        735	RECOVERED - MV RECOVERED IN BOSTON (STOLEN OUTSIDE BOSTON)
        3620	REPORT AFFECTING OTHER DEPTS.
        301	ROBBERY - STREET
        311	ROBBERY - COMMERCIAL
        351	ROBBERY - BANK
        361	ROBBERY - OTHER
        371	ROBBERY - HOME INVASION
        381	ROBBERY - CAR JACKING
        3403	PROTECTIVE CUSTODY / SAFEKEEPING
        3109	SERVICE TO OTHER PD INSIDE OF MA.
        3110	SERVICE TO OTHER PD OUTSIDE OF MA.
        1730	SEXUAL ASSAULT INVESTIGATION
        3006	SICK/INJURED/MEDICAL - PERSON
        801	ASSAULT - SIMPLE
        804	STALKING
        1704	STATUTORY RAPE
        2647	THREATS TO DO BODILY HARM
        3410	TOWED MOTOR VEHICLE
        2610	TRESPASSING
        2642	TRUANCY / RUNAWAY
        2915	VAL - MISCELLANEOUS
        2907	VAL - OPERATING AFTER REV/SUSP.
        2906	VAL - OPERATING UNREG/UNINS  CAR
        2905	VAL - OPERATING WITHOUT LICENSE
        2914	VAL - OPERATING W/O AUTHORIZATION LAWFUL
        1402	VANDALISM
        1415	GRAFFITI
        3301	VERBAL DISPUTE
        2657	VIOLATION - CITY ORDINANCE
        2641	VIOLATION - HAWKER AND PEDDLER
        2006	VIOL. OF RESTRAINING ORDER W ARREST
        2663	VIOLATION - CITY ORDINANCE CONSTRUCTION PERMIT
        3125	WARRANT ARREST
        2511	KIDNAPPING - ENTICING OR ATTEMPTED
        2613	ANIMAL ABUSE
        3002	ANIMAL CONTROL - DOG BITES - ETC.
        3402	ANIMAL INCIDENTS
        802	ASSAULT SIMPLE - BATTERY
        423	ASSAULT - AGGRAVATED
        413	ASSAULT - AGGRAVATED - BATTERY
        724	AUTO THEFT
        727	AUTO THEFT - LEASED/RENTED VEHICLE
        706	AUTO THEFT - MOTORCYCLE / SCOOTER
        541	BURGLARY - COMMERICAL - ATTEMPT
        540	BURGLARY - COMMERICAL - FORCE
        562	BURGLARY - OTHER - NO FORCE
        561	BURGLARY - OTHER - ATTEMPT
        542	BURGLARY - COMMERICAL - NO FORCE
        521	BURGLARY - RESIDENTIAL - ATTEMPT
        520	BURGLARY - RESIDENTIAL - FORCE
        522	BURGLARY - RESIDENTIAL - NO FORCE
        560	BURGLARY - OTHER - FORCE
        2662	BALLISTICS EVIDENCE/FOUND
        2672	BIOLOGICAL THREATS
        2648	BOMB THREAT
        2004	CHILD ABANDONMENT (NO ASSAULT)
        2003	CHILD ENDANGERMENT (NO ASSAULT)
        2608	CHINS
        1106	FRAUD - CREDIT CARD / ATM FRAUD
        2617	CONSPIRACY EXCEPT DRUG LAW
        2605	CONTRIBUTING TO DELINQUENCY OF MINOR
        1001	FORGERY / COUNTERFEITING
        2670	CRIMINAL HARASSMENT
        3625	DANGEROUS OR HAZARDOUS CONDITION
        2405	DISORDERLY CONDUCT
        1825	DRUGS - POSSESSION OF DRUG PARAPHANALIA
        1815	DRUGS - POSSESSION
        1832	DRUGS - SICK ASSIST - OTHER HARMFUL DRUG
        1831	DRUGS - SICK ASSIST - OTHER NARCOTIC
        2609	DRUGS - GLUE INHALATION
        1810	DRUGS - SALE / MANUFACTURING
        1830	DRUGS - SICK ASSIST - HEROIN
        1201	EMBEZZLEMENT
        2632	EVADING FARE
        2618	EXPLOSIVES - POSSESSION OR USE
        3123	EXPLOSIVES - TURNED IN OR FOUND
        2604	EXTORTION OR BLACKMAIL
        1721	FAILURE TO REGISTER AS A SEX OFFENDER
        3160	FIRE REPORT - CAR, BRUSH, ETC.
        3108	FIRE REPORT - HOUSE, BUILDING, ETC.
        2612	FIRE REPORT/ALARM - FALSE
        3016	FIREARM/WEAPON - ACCIDENTAL INJURY / DEATH
        1501	WEAPON - FIREARM - CARRYING / POSSESSING, ETC
        3203	FIREARM/WEAPON - LOST
        1503	WEAPON - OTHER - CARRYING / POSSESSING, ETC
        1502	WEAPON - FIREARM - SALE / TRAFFICKING
        1102	FRAUD - FALSE PRETENSE / SCHEME
        1107	FRAUD - IMPERSONATION
        1108	FRAUD - WELFARE
        2619	FUGITIVE FROM JUSTICE
        1901	GAMBLING - BETTING / WAGERING
        3302	GATHERING CAUSING ANNOYANCE
        3116	HARBOR INCIDENT / VIOLATION
        1702	INDECENT ASSAULT AND BATTERY
        1703	INDECENT EXPOSURE / LEWD AND LASCIVIOUS
        3004	INJURY BICYCLE NO M/V INVOLVED
        3170	INTIMIDATING WITNESS
        3115	INVESTIGATE PERSON
        3114	INVESTIGATE PROPERTY
        2622	KIDNAPPING/CUSTODIAL KIDNAPPING
        112	KILLING OF FELON BY POLICE
        114	KILLING OF POLICE BY FELON
        3112	LANDLORD - TENANT SERVICE
        616	LARCENY THEFT OF BICYCLE
        618	LARCENY THEFT FROM COIN-OP MACHINE
        617	LARCENY THEFT FROM BUILDING
        614	LARCENY THEFT FROM MV - NON-ACCESSORY
        619	LARCENY ALL OTHERS
        611	LARCENY PICK-POCKET
        3130	SEARCH WARRANT
        3018	SICK/INJURED/MEDICAL - POLICE
        3008	SUICIDE / SUICIDE ATTEMPT
        900	ARSON
        1504	WEAPON - OTHER - OTHER VIOLATION
        1510	WEAPON - FIREARM - OTHER VIOLATION
        1902	GAMBLING - OPERATING/PROMOTING/ASSISTING
        1903	GAMBLING - EQUIP VIOLATIONS
        1904	GAMBLING - SPORTS TAMPERING
        2101	OPERATING UNDER THE INFLUENCE ALCOHOL
        2629	HARASSMENT
        3102	INVESTIGATION FOR ANOTHER AGENCY
        3304	NOISY PARTY/RADIO-NO ARREST
        3811	M/V ACCIDENT - INVOLVING BICYCLE - NO INJURY
        3820	M/V ACCIDENT INVOLVING PEDESTRIAN - INJURY
        3821	M/V ACCIDENT - INVOLVING PEDESTRIAN - NO INJURY
        3830	M/V - LEAVING SCENE - PERSONAL INJURY
        3831	M/V - LEAVING SCENE - PROPERTY DAMAGE
        3007	SUDDEN DEATH
        3001	DEATH INVESTIGATION
        111	MURDER NON-NEGLIGIENT MANSLAUGHTER
        112	KILLING OF FELON BY POLICE
        114	KILLING OF POLICE BY FELON
        121	MANSLAUGHTER - VEHICLE - NEGLIGENCE
        122	MANSLAUGHTER - TRAIN ETC. VICTIM NON-NEGLIGENCE
        123	MANSLAUGHTER - NON-VEHICLE - NEGLIGENCE
        124	MANSLAUGHTER - VEHICLE - NEGLIGENCE OF VICTIM
        125	MANSLAUGHTER - TRAIN ETC. VICTIM NEGLIGENCE
        222	RAPE - FEMALE ATTEMPT FORCE
        230	RAPE - FEMALE - ATTEMPT FORCE - UNDER 16
        236	RAPE - FEMALE - DRUGGING
        211	RAPE - FEMALE - FORCE
        232	RAPE - FEMALE FORCE - UNDER 16
        233	RAPE - FEMALE - FORCE UNDER 16 DSS/DA REFFERRAL
        224	RAPE - FEMALE/MALE - ATTEMPT FORCE - UNNATURAL ACT
        213	RAPE - FEMALE/MALE - FORCE - UNNATURAL ACT
        223	RAPE - MALE - ATTEMPT FORCE
        231	RAPE - MALE - ATTEMPT FORCE - UNDER 16
        237	RAPE - MALE - DRUGGING
        212	RAPE - MALE - FORCE
        234	RAPE - MALE - FORCE - UNDER 16
        235	RAPE - MALE - FORCE - UNDER 16 DSS/DA REFERRAL
        301	ROBBERY - FIREARM - BANK
        302	ROBBERY - FIREARM - BUSINESS
        304	ROBBERY - FIREARM - GAS STATION
        303	ROBBERY - FIREARM - CHAIN STORE
        305	ROBBERY - FIREARM  - MISCELLANEOUS
        306	ROBBERY - FIREARM - RESIDENCE
        307	ROBBERY - FIREARM - STREET
        308	ROBBERY - FIREARM - TAXI
        309	ROBBERY - KNIFE - BANK
        310	ROBBERY - KNIFE - BUSINESS
        311	ROBBERY - KNIFE - CHAIN STORE
        312	ROBBERY - KNIFE - GAS STATION
        313	ROBBERY - KNIFE - MISCELLANEOUS
        314	ROBBERY - KNIFE- RESIDENCE
        315	ROBBERY - KNIFE - STREET
        316	ROBBERY - KNIFE - TAXI
        317	ROBBERY - OTHER WEAPON - BANK
        318	ROBBERY - OTHER WEAPON - BUSINESS
        319	ROBBERY - OTHER WEAPON - CHAIN STORE
        320	ROBBERY - OTHER WEAPON - GAS STATION
        321	ROBBERY - OTHER WEAPON - MISCELLANEOUS
        322	ROBBERY - OTHER WEAPON - RESIDENCE
        323	ROBBERY - OTHER WEAPON - STREET
        324	ROBBERY - OTHER WEAPON - TAXI
        333	ROBBERY - UNARMED - BANK
        334	ROBBERY - UNARMED - BUSINESS
        335	ROBBERY - UNARMED - CHAIN STORE
        336	ROBBERY - UNARMED - GAS STATION
        337	ROBBERY - UNARMED - MISCELLANEOUS
        338	ROBBERY - UNARMED - RESIDENCE
        339	ROBBERY - UNARMED - STREET
        340	ROBBERY - UNARMED - TAXI
        341	ROBBERY ATTEMPT - FIREARM - BANK
        342	ROBBERY ATTEMPT - FIREARM - BUSINESS
        343	ROBBERY ATTEMPT - FIREARM - CHAIN STORE
        344	ROBBERY ATTEMPT - FIREARM - GAS STATION
        345	ROBBERY ATTEMPT - FIREARM - MISCELLANEOUS
        346	ROBBERY ATTEMPT - FIREARM - RESIDENCE
        347	ROBBERY ATTEMPT - FIREARM - STREET
        348	ROBBERY ATTEMPT - FIREARM - TAXI
        349	ROBBERY ATTEMPT - KNIFE - BANK
        350	ROBBERY ATTEMPT - KNIFE - BUSINESS
        351	ROBBERY ATTEMPT - KNIFE - CHAIN STORE
        352	ROBBERY ATTEMPT - KNIFE - GAS STATION
        353	ROBBERY ATTEMPT - KNIFE - MISCELLANEOUS
        354	ROBBERY ATTEMPT - KNIFE - RESIDENCE
        355	ROBBERY ATTEMPT - KNIFE - STREET
        356	ROBBERY ATTEMPT - KNIFE - TAXI
        357	ROBBERY ATTEMPT - OTHER WEAPON - BANK
        358	ROBBERY ATTEMPT - OTHER WEAPON - BUSINESS
        359	ROBBERY ATTEMPT - OTHER WEAPON - CHAIN STORE
        360	ROBBERY ATTEMPT - OTHER WEAPON - GAS STATION
        361	ROBBERY ATTEMPT - OTHER WEAPON - MISCELLANEOUS
        362	ROBBERY ATTEMPT - OTHER WEAPON - RESIDENCE
        363	ROBBERY ATTEMPT - OTHER WEAPON - STREET
        364	ROBBERY ATTEMPT - OTHER WEAPON - TAXI
        373	ROBERRY ATTEMPT - UNARMED - BANK
        374	ROBERRY ATTEMPT - UNARMED - BUSINESS
        375	ROBERRY ATTEMPT - UNARMED - CHAIN STORE
        376	ROBERRY ATTEMPT - UNARMED - GAS STATION
        377	ROBERRY ATTEMPT - UNARMED - MISCELLANEOUS
        378	ROBERRY ATTEMPT - UNARMED - RESIDENCE
        379	ROBERRY ATTEMPT - UNARMED - STREET
        380	ROBERRY ATTEMPT - UNARMED - TAXI
        401	ASSAULT & BATTERY D/W - GUN
        402	ASSAULT & BATTERY D/W - KNIFE
        403	ASSAULT & BATTERY D/W - OTHER
        404	A&B HANDS, FEET, ETC.  - MED. ATTENTION REQ.
        411	ASSAULT D/W - GUN
        412	ASSAULT D/W - KNIFE
        413	ASSAULT D/W - OTHER
        421	ASSAULT & BATTERY D/W - GUN ON POLICE OFFICER
        422	ASSAULT & BATTERY D/W - KNIFE ON POLICE OFFICER
        423	ASSAULT & BATTERY D/W - OTHER ON POLICE OFFICER
        424	A&B HANDS, FEET, ETC. - PO MED. ATTENTION REQ. 
        431	ASSAULT D/W - GUN ON POLICE OFFICER
        432	ASSAULT D/W - KNIFE ON POLICE OFFICER
        433	ASSAULT D/W - OTHER ON POLICE OFFICER
        520	B&E RESIDENCE DAY - FORCE
        521	B&E RESIDENCE DAY - ATTEMPT FORCE
        522	B&E RESIDENCE DAY - NO FORCE
        527	B&E RESIDENCE DAY - NO PROP TAKEN
        510	B&E RESIDENCE NIGHT - FORCE
        511	B&E RESIDENCE NIGHT - ATTEMPT FORCE
        512	B&E RESIDENCE NIGHT - NO FORCE
        517	B&E RESIDENCE NIGHT - NO PROP TAKEN
        540	B&E NON-RESIDENCE DAY - FORCIBLE
        541	B&E NON-RESIDENCE DAY - ATTEMPT FORCE
        542	B&E NON-RESIDENCE DAY - NO FORCE
        543	B&E NON-RESIDENCE DAY - NO PROP TAKEN
        547	B&E NON-RESIDENCE DAY - NO PROP TAKEN
        530	B&E NON-RESIDENCE NIGHT - FORCE
        531	B&E NON-RESIDENCE NIGHT - ATTEMPT FORCE
        532	B&E NON-RESIDENCE NIGHT - NO FORCE
        537	B&E NON-RESIDENCE NIGHT - NO PROP TAKEN
        616	LARCENY BICYCLE $200 & OVER
        626	LARCENY BICYCLE $50 TO $199
        636	LARCENY BICYCLE UNDER $50
        618	LARCENY FROM COIN MACHINE $200 AND OVER
        628	LARCENY FROM COIN MACHINE $50 TO $199
        638	LARCENY FROM COIN MACHINE UNDER $50
        617	LARCENY IN A BUILDING $200 & OVER
        627	LARCENY IN A BUILDING $50 TO $199
        637	LARCENY IN A BUILDING UNDER $50
        614	LARCENY NON-ACCESSORY FROM VEH. $200 & OVER
        624	LARCENY NON-ACCESSORY FROM VEH. $50 TO $199
        634	LARCENY NON-ACCESSORY FROM VEH. UNDER $50
        619	LARCENY OTHER $200 & OVER
        629	LARCENY OTHER $50 TO $199
        639	LARCENY OTHER UNDER $50
        621	LARCENY PICK-POCKET $50 TO $199
        611	LARCENY PICK-POCKET $200 & OVER
        631	LARCENY PICK-POCKET UNDER $50
        612	LARCENY PURSE SNATCH INCL.NO FORCE $200 & OVER
        622	LARCENY PURSE SNATCH INCL.NO FORCE $50 TO $199
        632	LARCENY PURSE SNATCH INCL.NO FORCE UNDER $50
        613	LARCENY SHOPLIFTING $200 & OVER
        623	LARCENY SHOPLIFTING $50 TO $199
        633	LARCENY SHOPLIFTING UNDER $50
        615	LARCENY VEH. ACCESSORY $200 & OVER
        625	LARCENY VEH. ACCESSORY $50 & $199
        635	LARCENY VEH. ACESSORY UNDER $50
        649	LARCENY MOTOR VEHICLE PLATES
        723	ATTEMPTED AUTO THEFT
        724	AUTO THEFT
        727	AUTO THEFT LEASE/RENT VEHICLE
        735	AUTO THEFT - OUTSIDE - RECOVERED IN BOSTON
        770	AUTO THEFT - RECOVERED IN BY POLICE
        780	AUTO THEFT - RECOVERED IN BY OTHER
        790	AUTO THEFT - RECOVERED OUTSIDE
        801	SIMPLE ASSAULT
        802	ASSAULT & BATTERY
        803	A&B ON POLICE OFFICER
        901	ARSON - SINGLE DWELLING
        902	ARSON - MULTIPLE DWELLING
        905	ARSON - OTHER COMMERCIAL
        906	ARSON - COMMUNITY/PUB.STRUC.
        907	ARSON - ALL OTHER STRUCTURES
        920	ARSON - MOTOR VEHICLES
        930	ARSON - OTHER
        2611	ABDUCTION - INTICING
        2401	AFFRAY
        3122	AIRCRAFT INCIDENTS
        2613	ANIMAL ABUSE
        2407	ANNOYING AND ACCOSTING
        3002	ANIMAL CONTROL - DOG BITES - ETC.
        3402	ANIMAL INCIDENTS
        2662	BALLISTICS EVIDENCE/FOUND
        2648	BOMB THREAT
        2672	BIOLOGICAL THREATS
        2004	CHILD ABANDONMENT
        2003	CHILD ABUSE
        2005	CHILD ENDANGERMENT
        2608	CHINS
        1103	CONFIDENCE GAMES
        2617	CONSPIRACY EXCEPT DRUG LAW
        2605	CONTRIBUTING TO DELINQUENCY OF MINOR
        1001	COUNTERFEITING
        2670	CRIMINAL HARRASSMENT
        2664	CUSTODIAL KIDNAPPING
        3625	DANGEROUS OR HAZARDOUS CONDITION
        3040	DEATH INVESTIGATION
        3305	DEMONSTRATIONS/RIOT
        2405	DISORDERLY PERSON
        2403	DISTURBING THE PEACE
        1870	DRUGS - CONSP TO VIOL CONTROLLED SUBSTANCE
        2609	DRUGS - GLUE INHALATION
        1874	DRUGS - OTHER
        1842	DRUGS - POSS CLASS A - HEROIN, ETC. 
        1841	DRUGS - POSS CLASS A - INTENT TO MFR DIST DISP
        1849	DRUGS - POSS CLASS B - COCAINE, ETC.
        1848	DRUGS - POSS CLASS B - INTENT TO MFR DIST DISP
        1858	DRUGS - POSS  CLASS C
        1855	DRUGS - POSS CLASS C - INTENT TO MFR DIST DISP
        1864	DRUGS - POSS CLASS D - INTENT MFR DIST DISP
        1863	DRUGS - POSS CLASS D - MARIJUANA, ETC.
        1866	DRUGS - POSS CLASS E INTENT TO MF DIST DISP
        1868	DRUGS - POSS. HYPODERMIC NEEDLE
        1843	DRUGS - PRESENT AT HEROIN
        3023	DRUGS - SICK ASSIST - HARMFUL DRUG
        3021	DRUGS - SICK ASSIST - HEROIN
        1875	DRUGS - SICK ASSIST - METH
        3022	DRUGS - SICK ASSIST - OTHER NARCOTICS
        1847	DRUGS - TRAFFICKING IN COCAINE
        1840	DRUGS - TRAFFICKING IN HEROIN
        1873	DRUGS - UTTERING PRESC CONT SUBSTANCE
        1201	EMBEZZLEMENT
        3404	ESCORT DUTY
        2632	EVADING FARE
        3107	EXPLOSIONS
        2618	EXPLOSIVES - POSSESSION OR USE
        3123	EXPLOSIVES - TURNED IN OR FOUND
        2604	EXTORTION OR BLACKMAIL
        1721	FAILURE TO REGISTER AS A SEX OFFENDER
        3160	FIRE REPORT - CAR, BRUSH, ETC
        3108	FIRE REPORT - HOUSE, BUILDING, ETC. 
        2602	FIRE REPORT/ALARM - FALSE
        3017	FIREARM/WEAPON - ACCIDENTAL DEATH
        3016	FIREARM/WEAPON - ACCIDENTAL INJURY
        1501	FIREARM/WEAPON - CARRY - SELL - RENT
        3119	FIREARM/WEAPON - FOUND OR CONFISCATED
        3203	FIREARM/WEAPON - LOST 
        3204	FIREARM/WEAPON - LOST THEN LOCATED
        1503	FIREARM/WEAPON - POSSESSION OF DANGEROUS
        1502	FIREARM/WEAPON - VIOLATION
        1002	FORGERY OR UTTERING
        1102	FRAUD - FALSE PRETENSE
        1107	FRAUD - LARCENY BY SCHEME
        1105	FRAUDS - ALL OTHER
        2619	FUGITIVE FROM JUSTICE
        1921	GAMING OFFENSE
        3302	GATHERING CAUSING ANNOYANCE
        2651	HARBOR - VIOL. REGS. & STATUTES
        3116	HARBOR INCIDENTS
        3151	HARASSING PHONE CALLS 
        1727	INDECENT A&B MENTALLY RETARDED PERSON 14 AND OVER
        1726	INDECENT A&B MENTALLY RETARDED PERSON UNDER 14
        1725	INDECENT A&B ON PERSON 14 AND OVER
        1702	INDECENT A&B ON PERSON UNDER 14
        1703	INDECENT EXPOSURE
        3170	INTIMIDATING WITNESS
        3114	INVESTIGATE PROPERTY
        3115	INVESTIGATE PERSON
        3152	INVESTIGATION FOR ANOTHER AGENCY
        2622	KIDNAPPING - FORCE
        3117	LABOR & STRIKE REPORT
        3112	LANDLORD - TENANT SERVICE
        1710	LEWD & LACIVIOUS SPEECH & BEHAVIOR
        3111	LICENSE PREMISE VIOLATION
        2646	LIQUOR - DRINKING IN PUBLIC
        2202	LIQUOR - ILLEGAL SALES - KEEPING - EXPOSING
        2201	LIQUOR - INVOLVING MINORS
        2204	LIQUOR - VIOLATION 
        3412	LOCKOUT SERVICE
        2908	M/V - LEAVING SCENE - PERSONAL INJURY
        2907	M/V - LEAVING SCENE - PROPERTY DAMAGE
        3712	M/V ACCIDENT - OTHER
        3706	M/V ACCIIDENT - OTHER CITY VEHICLE
        3702	M/V ACCIDENT - PERSONAL INJURY
        3704	M/V ACCIDENT - POLICE VEHICLE
        3701	M/V ACCIDENT - PROPERTY DAMAGE
        3205	M/V PLATES - LOST
        3206	M/V PLATES - LOST THEN LOCATED
        3501	MISSING PERSON
        3502	MISSING PERSON - LOCATED
        3503	MISSING PERSON - NOT REPORTED - LOCATED
        3303	NOISY PARTY/RADIO/ETC.
        2623	OBSCENE MATERIALS - PORNOGRAPHY
        2628	OBSCENE PHONE CALLS
        1711	OPEN & GROSS LEWDNESS
        2101	OPERATING UNDER INFLUENCE - ALCOHOL
        2102	OPERATING UNDER INFLUENCE - DRUGS
        2660	OTHER OFFENSE
        2665	OTHER SERVICE/EVENT
        2801	PARKING VIOLATION
        1101	PASSING WORTHLESS CHECK
        3408	POLICE SERVICES - TRAFFIC ETC.
        2616	POSSESSION OF BURGLARIOUS TOOLS
        2606	PRISONER - ATTEMPT TO RESCUE
        2636	PRISONER - ESCAPE
        2668	PRISONER - ESCAPE AND RECAPTURED
        2667	PRISONER - SUICIDE
        3029	PRISONER - SUICIDE ATTEMPT
        3210	PROPERTY - ABANDONED
        3106	PROPERTY - ACCIDENTAL DAMAGE
        1301	PROPERTY - BUYING STOLEN
        2631	PROPERTY - CONCEALING LEASED
        3207	PROPERTY - FOUND
        3201	PROPERTY - LOST
        3202	PROPERTY - LOST THEN LOCATED
        3208	PROPERTY - MISSING
        3209	PROPERTY - MISSING THEN LOCATED
        1302	PROPERTY - RECEIVING STOLEN
        1304	PROPERTY - STOLEN THEN RECOVERED
        1605	PROSTITUTE - COMMON NIGHTWALKER
        1603	PROSTITUTE - DERIVING SUPPORT
        1601	PROSTITUTION
        1602	PROSTITUTION - SOLICITING
        1607	PROSTITUTION/VICE - OTHER
        3414	PROTECTIVE CUSTODY - ALCOHOL
        3612	RACIAL - RELIGIOUS REPORT
        670	RECOVERED STOLEN PLATE
        3620	REPORT AFFECTING OTHER DEPTS.
        2633	RUNAWAY
        3403	SAFEKEEPING
        3130	SEARCH WARRANT
        1723	SEARCH WARRANT - RAPE INVESTIGATION
        3109	SERVICE TO OTHER PD INSIDE OF MA.
        3110	SERVICE TO OTHER PD OUTSIDE OF MA.
        1720	SEX OFFENSE - OTHER
        3006	SICK/INJURED/MEDICAL - PERSON
        3018	SICK/INJURED/MEDICAL - POLICE
        1704	STATUTORY RAPE
        3007	SUDDEN DEATH
        3008	SUICIDE
        3009	SUICIDE ATTEMPT
        2647	THREATS TO DO BODILY HARM
        2917	TOWED M/V FOR INVESTIGATION
        3410	TOWED MOTOR VEHICLE
        2642	TRUANCY
        2610	TRESPASSING
        2902	VAL - DRIVING TO ENDANGER 
        2901	VAL - FAILING TO SLOW - INTERSECTION
        2912	VAL - M/V CURFEW RESTRICTING 16-18Y
        2909	VAL - M/V STICKER
        2915	VAL - MISCELLANEOUS
        2910	VAL - OPERATING AFTER REV/SUSP.
        2906	VAL - OPERATING UNREG/UNINS CAR
        2624	VAL - OPERATING W/O AUTHORIZATION LAWFUL
        2905	VAL - OPERATING WITHOUT LICENSE
        2916	VAL - PEDESTRIAN LAW
        2913	VAL - REFUSING TO STOP FOR POLICE
        2903	VAL - SPEEDING
        2904	VAL - TRAFFIC SIGNALS
        2911	VAL - USING M/V W/O AUTHORITY UNLAWFUL
        1402	VANDALISM
        1415	VANDALISM - GRAFFITI
        3301	VERBAL DISPUTE
        2650	VIOLATION - AUTO RENTAL LAWS
        2657	VIOLATION - CITY ORDINANCE
        2658	VIOLATION - HACKNEY REGS
        2641	VIOLATION - HAWKER AND PEDDLER
        2006	VIOLATION - RESTRAINING ORDER
        3125	WARRANT ARREST
        1401	WILFUL & MAL. DESTRUCTION OF PROPERTY W/ARREST
        701	AUTO THEFT TRUCK - RECOVERED IN BY POLICE
        702	AUTO THEFT TRUCK - RECOVERED IN BY OTHER
        704	AUTO THEFT TRUCK
        711	AUTO THEFT OTHER - RECOVERED IN BY POLICE
        712	AUTO THEFT OTHER - RECOVERED IN BY OTHER
        713	AUTO THEFT OTHER - RECOVERED OUTSIDE
        714	AUTO THEFT OTHER
        715	AUTO THEFT OTHER - OUTSIDE RECOVERED IN BOS
        804	STALKING
        900	ARSON
        706	AUTO THEFT - MOTORCYCLE
        2663	VIOLATION - CITY ORDINANCE CONSTRUCTION PERMIT
        113	KILLING OF FELON BY CITIZEN
        3709	M/V ACCIDENT - INVOLVING BICYCLE
        3004	INJURY BICYCLE NO M/V INVOLVED
        1720	SEX OFFENSE - OTHER
        1843	DRUGS - POSS CLASS B - INTENT TO MFR DIST DISP
        1844	DRUGS - POSS CLASS C
        1845	DRUGS - POSS CLASS D
        1846	DRUGS - POSS CLASS E
        2611	ABDUCTION - INTICING
        2401	AFFRAY
        3122	AIRCRAFT INCIDENTS
        2407	ANNOYING AND ACCOSTIN
        2005	CHILD ENDANGERMENT
        2664	CUSTODIAL KIDNAPPING
        3305	DEMONSTRATIONS / RIOT
        2403	DISTURBING THE PEACE
        1870	DRUGS - CONSP TO VIOL CONT SUB ACT
        1874	DRUGS - OTHER
        1842	DRUGS - POSS CLASS A - HEROIN, ETC.
        1841	DRUGS - POSS CLASS A - INTENT TO MFR DIST DISP
        1849	DRUGS - POSS CLASS B - COCAINE, ETC.
        3119	FIREARM/WEAPON - FOUND OR CONFISCATED
        1103	CONFIDENCE GAMES
        2608	CHINS
        1847	DRUGS - POSS CLASS C - INTENT TO MFR DIST DISP
        1848	DRUGS - POSS CLASS D - INTENT TO MFR DIST DISP
        1850	DRUGS - POSS CLASS E - INTENT TO MFR DIST DISP
        2631	PROPERTY - CONCEALING LEASED
        1805	DRUGS - CLASS A TRAFFICKING OVER 18 GRAMS
        1806	DRUGS - CLASS B TRAFFICKING OVER 18 GRAMS
        1807	DRUGS - CLASS D TRAFFICKING OVER 50 GRAMS
        1610	HUMAN TRAFFICKING - COMMERCIAL SEX ACTS
        2010	HOME INVASION
        1620	HUMAN TRAFFICKING - INVOLUNTARY SERVITUDE
        EOT;

        //strip all the new lines and repeated spaces or tabs
        $crimeContext = preg_replace('/\s+/', ' ', $crimeContext);
        return $crimeContext;
    }
}
