<style type="text/css">
	.active { display:inherit; }
	ul#map {display: block; margin:0px 0px 0px -50px; padding: 0; background: url('Mapa.png') no-repeat;width: 570px; height: 585px;}/* 570px */
	ul#map li {display: block; padding: 0; position: absolute;}
	li#crs {margin-top: 485px; margin-left: 237px;}
	li#csc {margin-top: 467px; margin-left: 292px;}
	li#cpr {margin-top: 416px; margin-left: 281px;}
	li#csp {margin-top: 375px; margin-left: 303px; z-index:1; }
	li#cms {margin-top: 336px; margin-left: 229px;}
	li#crj {margin-top: 390px; margin-left: 422px; z-index:2; }
	li#ces {margin-top: 347px; margin-left: 467px; z-index:2; }
	li#cmg {margin-top: 292px; margin-left: 333px; z-index:1; }
	li#cgo {margin-top: 264px; margin-left: 301px; z-index:1; }
	li#cdf {margin-top: 311px; margin-left: 373px; z-index:1; }
	li#cba {margin-top: 207px; margin-left: 397px;}
	li#cmt {margin-top: 189px; margin-left: 180px;}
	li#cro {margin-top: 199px; margin-left: 104px; z-index:1; }
	li#cac {margin-top: 185px; margin-left: 0px;}
	li#cam {margin-top: 46px; margin-left: 3px;}
	li#crr {margin-top: 0; margin-left: 133px;}
	li#cpa {margin-top: 40px; margin-left: 219px;}
	li#cap {margin-top: 13px; margin-left: 278px;}
	li#cma {margin-top: 94px; margin-left: 366px; z-index:1;}
	li#cto {margin-top: 156px; margin-left: 338px;}

	li#cse {margin-top: 221px; margin-left: 519px; z-index:1;}
	li#cal {margin-top: 211px; margin-left: 518px; z-index:1;}
	li#cpe {margin-top: 188px; margin-left: 473px;}
	li#cpb {margin-top: 169px; margin-left: 511px; z-index:1;}
	li#crn {margin-top: 151px; margin-left: 514px;}
	li#cce {margin-top: 121px; margin-left: 473px;}
	li#cpi {margin-top: 120px; margin-left: 406px; z-index:1;}

	ul#map li a {display: block; text-decoration: none; position: absolute;}
	a#rs {width: 116px; height: 101px; }
	a#sc {width: 81px; height: 53px; }
	a#pr {width: 97px; height: 64px; }
	a#sp {width: 131px; height: 84px; }
	a#ms {width: 106px; height: 104px; }
	a#rj {width: 58px; height: 40px; }
	a#es {width: 33px; height: 51px; }
	a#mg {width: 163px; height: 131px; }
	a#go {width: 108px; height: 108px; }
	a#df {width: 16px; height: 9px; }
	a#ba {width: 136px; height: 148px; }
	a#mt {width: 166px; height: 161px; }
	a#ro {width: 104px; height: 87px; }
	a#ac {width: 108px; height: 62px; }
	a#am {width: 258px; height: 181px;}
	a#rr {width: 87px; height: 103px; }
	a#pa {width: 188px; height: 187px; }
	a#ap {width: 73px; height: 85px; }
	a#ma {width: 102px; height: 139px; }
	a#to {width: 74px; height: 125px; }
	a#se {width: 28px; height: 32px; }
	a#al {width: 46px; height: 27px; }
	a#pe {width: 97px; height: 34px; }
	a#pb {width: 59px; height: 35px; }
	a#rn {width: 53px; height: 33px; }
	a#ce {width: 61px; height: 76px; }
	a#pi {width: 83px; height: 124px; }

	/* C�digo pronto via http://css.spritegen.com (com altera��es nos seletores) */

	a.ativo, a#pa:hover, a#pa:active, a#am:hover, a#am:active, a#mt:hover, a#mt:active, a#ba:hover, a#ba:active, a#ma:hover, a#ma:active,
	a#mg:hover, a#mg:active, a#to:hover, a#to:active, a#pi:hover, a#pi:active, a#go:hover, a#go:active, a#ms:hover, a#ms:active,
	a#rr:hover, a#rr:active, a#rs:hover, a#rs:active, a#ro:hover, a#ro:active, a#ap:hover, a#ap:active, a#sp:hover, a#sp:active,
	a#ce:hover, a#ce:active, a#pr:hover, a#pr:active, a#ac:hover, a#ac:active, a#sc:hover, a#sc:active, a#es:hover, a#es:active,
	a#rj:hover, a#rj:active, a#pb:hover, a#pb:active, a#pe:hover, a#pe:active, a#rn:hover, a#rn:active, a#se:hover, a#se:active,
	a#al:hover, a#al:active, a#df:hover, a#df:active
	{ display: block; background: url('sprite.gif') no-repeat;}

	a#pa:hover, a#pa:active { background-position: -10px -0px; width: 188px; height: 187px; }
	a#am:hover, a#am:active { background-position: -10px -197px; width: 258px; height: 181px; }
	a#mt:hover, a#mt:active { background-position: -10px -388px; width: 166px; height: 161px; }
	a#ba:hover, a#ba:active { background-position: -10px -559px; width: 136px; height: 148px; }
	a#ma:hover, a#ma:active { background-position: -156px -559px; width: 102px; height: 139px; }
	a#mg:hover, a#mg:active { background-position: -10px -717px; width: 163px; height: 131px; }
	a#to:hover, a#to:active { background-position: -183px -717px; width: 74px; height: 125px; }
	a#pi:hover, a#pi:active { background-position: -10px -858px; width: 83px; height: 124px; }
	a#go:hover, a#go:active { background-position: -103px -858px; width: 108px; height: 108px; }
	a#ms:hover, a#ms:active { background-position: -103px -976px; width: 106px; height: 104px; }
	a#rr:hover, a#rr:active { background-position: -10px -992px; width: 87px; height: 103px; }
	a#rs:hover, a#rs:active { background-position: -107px -1090px; width: 116px; height: 101px; }
	a#ro:hover, a#ro:active { background-position: -10px -1201px; width: 104px; height: 87px; }
	a#ap:hover, a#ap:active { background-position: -10px -1105px; width: 73px; height: 85px; }
	a#sp:hover, a#sp:active { background-position: -124px -1201px; width: 131px; height: 84px; }
	a#ce:hover, a#ce:active { background-position: -186px -388px; width: 61px; height: 76px; }
	a#pr:hover, a#pr:active { background-position: -124px -1295px; width: 97px; height: 64px; }
	a#ac:hover, a#ac:active { background-position: -10px -1298px; width: 108px; height: 62px; }
	a#sc:hover, a#sc:active { background-position: -128px -1369px; width: 81px; height: 53px; }
	a#es:hover, a#es:active { background-position: -208px -0px; width: 33px; height: 51px; }
	a#rj:hover, a#rj:active { background-position: -186px -474px; width: 58px; height: 40px; }
	a#pb:hover, a#pb:active { background-position: -10px -1370px; width: 59px; height: 35px; }
	a#pe:hover, a#pe:active { background-position: -10px -1415px; width: 97px; height: 34px; }
	a#rn:hover, a#rn:active { background-position: -186px -524px; width: 53px; height: 33px; }
	a#se:hover, a#se:active { background-position: -208px -61px; width: 28px; height: 32px; }
	a#al:hover, a#al:active { background-position: -208px -103px; width: 46px; height: 27px; }
	a#df:hover, a#df:active { background-position: -208px -140px; width: 16px; height: 9px; }

	ul#map li#cpa a.ativo { background-position: -10px -0px; width: 188px; height: 187px; }
	ul#map li#cam a.ativo { background-position: -10px -197px; width: 258px; height: 181px; }
	ul#map li#cmt a.ativo { background-position: -10px -388px; width: 166px; height: 161px; }
	ul#map li#cba a.ativo { background-position: -10px -559px; width: 136px; height: 148px; }
	ul#map li#cma a.ativo { background-position: -156px -559px; width: 102px; height: 139px; }
	ul#map li#cmg a.ativo { background-position: -10px -717px; width: 163px; height: 131px; }
	ul#map li#cto a.ativo { background-position: -183px -717px; width: 74px; height: 125px; }
	ul#map li#cpi a.ativo { background-position: -10px -858px; width: 83px; height: 124px; }
	ul#map li#cgo a.ativo { background-position: -103px -858px; width: 108px; height: 108px; }
	ul#map li#cms a.ativo { background-position: -103px -976px; width: 106px; height: 104px; }
	ul#map li#crr a.ativo { background-position: -10px -992px; width: 87px; height: 103px; }
	ul#map li#crs a.ativo { background-position: -107px -1090px; width: 116px; height: 101px; }
	ul#map li#cro a.ativo { background-position: -10px -1201px; width: 104px; height: 87px; }
	ul#map li#cap a.ativo { background-position: -10px -1105px; width: 73px; height: 85px; }
	ul#map li#csp a.ativo { background-position: -124px -1201px; width: 131px; height: 84px; }
	ul#map li#cce a.ativo { background-position: -186px -388px; width: 61px; height: 76px; }
	ul#map li#cpr a.ativo { background-position: -124px -1295px; width: 97px; height: 64px; }
	ul#map li#cac a.ativo { background-position: -10px -1298px; width: 108px; height: 62px; }
	ul#map li#csc a.ativo { background-position: -128px -1369px; width: 81px; height: 53px; }
	ul#map li#ces a.ativo { background-position: -208px -0px; width: 33px; height: 51px; }
	ul#map li#crj a.ativo { background-position: -186px -474px; width: 58px; height: 40px; }
	ul#map li#cpb a.ativo { background-position: -10px -1370px; width: 59px; height: 35px; }
	ul#map li#cpe a.ativo { background-position: -10px -1415px; width: 97px; height: 34px; }
	ul#map li#crn a.ativo { background-position: -186px -524px; width: 53px; height: 33px; }
	ul#map li#cse a.ativo { background-position: -208px -61px; width: 28px; height: 32px; }
	ul#map li#cal a.ativo { background-position: -208px -103px; width: 46px; height: 27px; }
	ul#map li#cdf a.ativo { background-position: -208px -140px; width: 16px; height: 9px; }				
	
	/* Fim sprite */

	ul#map li a{cursor:pointer;}
	ul#map li a img {border: 0; width: inherit; height: inherit;}
</style>




<div align="center" class="span7">
	<ul id="map">
		<li estado="rs" id="crs"><a class="" title="RS" id="rs"><img alt="RS" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="sc" id="csc"><a class="" title="SC" id="sc"><img alt="SC" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="pr" id="cpr"><a class="" title="PR" id="pr"><img alt="PR" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="sp" id="csp"><a class="" title="SP" id="sp"><img alt="SP" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="ms" id="cms"><a class="" title="MS" id="ms"><img alt="MS" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="rj" id="crj"><a class="" title="RJ" id="rj"><img alt="RJ" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="es" id="ces"><a class="" title="ES" id="es"><img alt="ES" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="mg" id="cmg"><a class="" title="MG" id="mg"><img alt="MG" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="go" id="cgo"><a class="" title="GO" id="go"><img alt="GO" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="df" id="cdf"><a class="" title="DF" id="df"><img alt="DF" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="ba" id="cba"><a class="" title="BA" id="ba"><img alt="BA" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="mt" id="cmt"><a class="" title="MT" id="mt"><img alt="MT" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="ro" id="cro"><a class="" title="RO" id="ro"><img alt="RO" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="ac" id="cac"><a class="" title="AC" id="ac"><img alt="AC" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="am" id="cam"><a class="" title="AM" id="am"><img alt="AM" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="rr" id="crr"><a class="" title="RR" id="rr"><img alt="RR" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="pa" id="cpa"><a class="" title="PA" id="pa"><img alt="PA" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="ap" id="cap"><a class="" title="AP" id="ap"><img alt="AP" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="ma" id="cma"><a class="" title="MA" id="ma"><img alt="MA" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="to" id="cto"><a class="" title="TO" id="to"><img alt="TO" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="se" id="cse"><a class="" title="SE" id="se"><img alt="SE" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="al" id="cal"><a class="" title="AL" id="al"><img alt="AL" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="pe" id="cpe"><a class="" title="PE" id="pe"><img alt="PE" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="pb" id="cpb"><a class="" title="PB" id="pb"><img alt="PB" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="rn" id="crn"><a class="" title="RN" id="rn"><img alt="RN" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="ce" id="cce"><a class="" title="CE" id="ce"><img alt="CE" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
		<li estado="pi" id="cpi"><a class="" title="PI" id="pi"><img alt="PI" src="http://www.assecc.com.br/sitenovo/images/null.gif"></a></li>
	</ul>
</div>
