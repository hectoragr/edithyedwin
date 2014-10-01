<?php
// Enter your personal details here to build your online resume.

// Let's start with some basic info.  You must include your full name, but if you don't want to include your phone number or email address you can just delete those lines entirely.
$personal['first_name']= 'Héctor A.';
$personal['last_name'] = 'Gómez Reyes';
$personal['phone_number'] = '+528110388869';
$personal['email_address'] = 'hector.agr@gmail.com';
$settings['lang'] = isset($_GET['lang'])?$_GET['lang']:"en";
// Now let's add any social media pages you've created and want to share.  You can add whatever you want by following the format below.  The first item you add is the name of the social media site, and the second item you add is the URL to 
$social_media[] = array('LinkedIn', 'http://lnkd.in/cYv2iq');
$social_media[] = array('GitHub','https://github.com/hectoragr');

$work_history = array();
$education=array();
$skills=array();
$awards=array();
// Cool.  With that stuff out of the way, let's input your work history.  This part requires a bit more information but it's similar to adding a social media page like you did above but with more information.  The first item you enter is last year you worked at the job (or the range of time, if you prefer).  The second item is your job title, the third is the company you worked for, and the fourth is a description of the work you did there.  The description can get a little long, but that's okay.  Just be sure not to use any single quotes ('') or things will break!  You can add as many jobs as you like and the script will adapt.
switch ($settings['lang']) {
	case 'es':
		setSpanish();
		break;
	case 'de':
	default:
		setEnglish();
		break;
}

// Want to turn off any of the sections on your resume?  You can do that in the settings.  Just change any section from true to false and it'll disappear.  (Note: You can't turn off your work history--that's sort of important!)
$settings['social_media'] = true;
$settings['education'] = true;
$settings['skills'] = true;
$settings['awards'] = true;

// There are also a few other settings you can toggle on and off, such as offering a printable version of your resume and whether to use a dark or light style (set style to "dark" or "light" to choose).
$settings['printable'] = true;
$settings['style'] = "light";

function setEnglish(){
	global $work_history;
	global $skills;
	global $education;
	global $awards;
	$work_history[] = array('July 2013 - Present',
							'PHP Jr. Developer',
							'Metodika (<a href="www.metodika.mx">www.metodika.mx</a>)',
							'I have participated in different projects, some on my own others as a team. 
							One of them was adding functionalities for a Local Televesion company website. 
							I also wrote system modules for White pages websites, we create content administration tools for dynamic websites.
							And if the customer wants we design responsive websites or mobile focused. ');
	$work_history[] = array('April 2013',
							'Freelance',
							'Pressta.me (<a href="https://apps.facebook.com/Presstame/">https://apps.facebook.com/Presstame/</a>)',
							'Added HTML features and created a page for accesing documents uploaded 
							by the app’s users and follow the loan\'s progress. Corrected MySQL queries.');
	$work_history[] = array('December 2012 - March 2013',
							'PHP and MySQL developer (intern)',
							'Ávila Integradores (<a href="www.avilaintegradores.com">www.avilaintegradores.com</a>)',
							'Internship. Most of the work I was asigned was for a loan company, 
							which had to implement new functions for their system. 
							Those functions were a government requirement for a new law encouraging 
							to prevent Money Laundering. The greatest challenge was that the system\'s 
							DB had thousands of records and the way those records were filled, 
							prevented a straigh-forward solution. My solution was to come-up with a 
							query that was far more specific using several other databases. 
							This affected perfomance, yet complied with most of the government requirements.');
	$work_history[]=array('Before...',
						  'Customer service, salesman, Tech Coordinator, Programmer',
						  'Several places',
						  'My first job was at 17 as a customer bilingual billing and customer service agent. 
						  I did that for bit more than a year and decided that I needed to focus on school. 
						  Then I worked as Apple Salesman, and technician for about 9 months, and for one summer after that.
						  I was also Tech Coordinator for a school group whose mission was to build an elementary school library by gathering books and funds. 
						  I made a webpage for them and applied computer \'wizardry\' for schedule and attendance registring. 
						  I also work for about two months as PHP programmer but it did not last long due to financial struggles from that company.');

	// Phew!  That was the hard part.  Now we need to enter in some other important information about you.  Let's start with your education.  You only need to enter two things: your school and a description of your degree.  If you went to Brown University you'd enter that for your school, then put your year and graduation date as the description (e.g. "BA in Social Sciences, Class of 2002").
	$education[] = array('ITESM', 'BSc. Digital Systems and Robotics Eng. GPA:75/100');
	//$education[] = array('School', 'Description');

	// Presumably you learned something in school and developed a few skills over the course of your lifetime.  Let's add them here.  
	$skills[] = array('Programming', 
					  '<ul style="list-style-type:none">
					  	<li>PHP - Intermediate</li>
					  	<li>C - Intermediate</li>
					  	<li>Java - Intermediate</li>
					  	<li>Assembly x86/PIC-32 - Intermediate</li>
					  	<li>C# - Basic</li>
					  </ul>');
	$skills[] = array('Web development', 
					  '<ul style="list-style-type:none">
					  	<li>Front-end: HTML, CSS, JavaScript, JQuery</li>
					  	<li>Frameworks: MVC (pattern), CodeIgniter, Smarty, Bootstrap</li>
					  	<li>Tools: MAMP, XAMPP, MySQL Workbench, Aptana, TortoiseSVN, Git Hub</li>
					   </ul>');
	$skills[] = array('Operating Systems', 
					   '<ul style="list-style-type:none">
					   	 <li>Windows: User/Admin</li>
					   	 <li>Mac OS X: User/Admin/Support</li>
					   	 <li>Linux: User/Admin</li>
					    </ul>');

	// Finally, let's list any awards or honors you've received so you can show off your accomplishments.  To add an award, just use the format below.  (I'm sure you have the hang of this by now, but we're going to go over it anyway.)  The first item you enter is the name of the award/honor (e.g. "Advertising Gold Award" or "Published in the New Yorker") and the second item is the description of the award/honor (e.g. "2010 TV Ads Under $50,000" or "Short story about the trials of Orthodox Jews in Arkansas, May 2016").
	$awards[] = array('Compilers Course 2012', 
		              'I wrote the Virtual Machine wich used a driver to communicate with a Microcontroller, for which I also wrote the firmwae.
		              This was for a project which purpose was to have a C like language with built-in functions to read and write bytes or bits to the I/O 
		              of the microcontroller.');
	$awards[] = array('Internet Applications 2012', 
					  'Created a Point of Sale website with Shopping Cart and Checkout system using JSP, 
					  Ajax and other web technologies. Team of three. 
					  I Designed the Front-end and content rendering through JSP logic.');	
}

function setSpanish(){
	global $work_history;
	global $skills;
	global $education;
	global $awards;
	$work_history[] = array('Julio 2013 - Actualidad',
							'Desarrollador PHP Jr.',
							'Metodika (<a href="www.metodika.mx">www.metodika.mx</a>)',
							'He participado en varios proyectos. Algunos yo solo, otros en equipo.
							Entre ellos he agregado modulos para proveer funcionalidad a páginas y herramientas de administración para sitios de televisoras locales. 
							También cree escribí módulos para bolsas de trabajo que permitan administar la información obtenida. Cramos páginas dinamicas y que se puede administrar el contenido. Y si el cliente así lo requiere creamos páginas a la medida, responsivas o enfoncadas a móviles. ');
	$work_history[] = array('Abril 2013',
							'Freelance',
							'Pressta.me (<a href="https://apps.facebook.com/Presstame/">https://apps.facebook.com/Presstame/</a>)',
							'Agregué elementos graficos y de navegación, además de un mini sistema para adminsitar documentos subidos por usuarios de la aplicación y así dar seguimientos a los prestamos. Correción de consultas de MySQL.');
	$work_history[] = array('Diciembre 2012 - Marzo 2013',
							'Desarrollador PHP & MySQL (practicante)',
							'Ávila Integradores (<a href="www.avilaintegradores.com">www.avilaintegradores.com</a>)',
							'La mayoría del trabajo que realice durante mi estancia fue agregar funcionalidades a el sistema de una SOFOM para cumplir con requisitos del gobierno y la prevención del lavado de dinero. El mayor reto fue trabajar con las bases de datos que ya contenían miles de datos y cuya estructura estaba mal diseñada para una solución directa. Finalmente escribí una serie de consultas que permitian, a costo del desempeño, cumplir la mayoría de los requisitos.');
	$work_history[]=array('Antes...',
						  'Servicio al cliente, Vendedor, Técnico,<br> Coordinador técnico, Programador',
						  'Varios lugares',
						  'Mi primer trabajo fue a los 17 años como agente bilingüe para ventas y facturación de una compañía de Cable. Dicho trabajo duró poco más de un año antes de decidir enfocarme más en la escuela.
						  Después de eso trabaje como vendedor de Tecnología de Apple y técnico por cerca de 9 meses y un verano después. También fuí coordinador de tecnología para un servicio social cuya misión era construir bibliotecas para primarias. Realice la página web del grupo y automatice los registros de asistencia y actividades. 
						  También fuí programador PHP por dos meses para una compañía, hasta que la misma tuvo que suspender operaciones por problemas financieros.');

	// Phew!  That was the hard part.  Now we need to enter in some other important information about you.  Let's start with your education.  You only need to enter two things: your school and a description of your degree.  If you went to Brown University you'd enter that for your school, then put your year and graduation date as the description (e.g. "BA in Social Sciences, Class of 2002").
	$education[] = array('ITESM', 'Ing. en Sistemas Digitales y Robótica GPA:75/100');
	//$education[] = array('School', 'Description');

	// Presumably you learned something in school and developed a few skills over the course of your lifetime.  Let's add them here.  
	$skills[] = array('Programación', 
					  '<ul style="list-style-type:none">
					  	<li>PHP - Avanzado</li>
					  	<li>C - Intermedio</li>
					  	<li>Java - Intermedio</li>
					  	<li>Assembly x86/PIC-32 - Intermedio</li>
					  	<li>C# - Básico</li>
					  </ul>');
	$skills[] = array('Desarrollo web', 
					  '<ul style="list-style-type:none">
					  	<li>Front-end: HTML, CSS, JavaScript, JQuery</li>
					  	<li>Frameworks: MVC (pattern), CodeIgniter, Smarty, Bootstrap</li>
					  	<li>Herramientas: MAMP, XAMPP, MySQL Workbench, Aptana, TortoiseSVN, Git Hub</li>
					   </ul>');
	$skills[] = array('Sistemas Operativos', 
					   '<ul style="list-style-type:none">
					   	 <li>Windows: Usario/Administrador</li>
					   	 <li>Mac OS X: Usario/Administrador/Soporte</li>
					   	 <li>Linux: Usario/Administrador</li>
					    </ul>');

	// Finally, let's list any awards or honors you've received so you can show off your accomplishments.  To add an award, just use the format below.  (I'm sure you have the hang of this by now, but we're going to go over it anyway.)  The first item you enter is the name of the award/honor (e.g. "Advertising Gold Award" or "Published in the New Yorker") and the second item is the description of the award/honor (e.g. "2010 TV Ads Under $50,000" or "Short story about the trials of Orthodox Jews in Arkansas, May 2016").
	$awards[] = array('Lenguajes y traductores 2012', 
		              'Escribí una máquina virtual en C que utilizaba un driver para comunicarse por USB con un microcontrolador. Dicha MV enviaba o recibia información de los puertos E/S del microcontrolador del cual también escribí el firmware. El próposito de este proyecto de dos personas era un lenguaje con sintaxis similar a C con funciones nativas para escribir y leer bits o bytes del microcontrolador.');
	$awards[] = array('Desarrollo de Aplicaciones Distribuidas 2012', 
					  'Diseñe el front-end y controladores de un Sistema de Venta en línea con carrito de compras utilizando JSP, AJAx y otras tecnologías web.');	
}


?>