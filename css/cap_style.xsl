<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
    xmlns:cap="urn:oasis:names:tc:emergency:cap:1.2">

<xsl:template match="/cap:alert">
	<html>
		<head>
			<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
			<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />
		</head>
		<body>
			<style>
				html, body {
					height: 100%;
					width: 100%;
					margin: 0px;
					padding: 0px;
					font-family: Verdana, Arial, Helvetica, sans-serif;
					font-size: 11px;
				}
				a {
					color: #000;
					text-decoration: none;
				}
				.container {
					margin: 3px;
					width: auto;
					height: auto;
				}
				.head1 {
					color:#FFFFFF;
					background: url(http://meteoalarm.eu/images/header_bg.png);
					margin-bottom: 3px;
					padding-left: 10px;
					height: 35px;
					line-height: 35px;
					font-size: 20px;
				}
				.head2 {
					position: relative;
					background-color:#AEE458;
					margin-bottom: 3px;
					min-height: 25px;
					line-height: 25px;
					padding-left: 10px;
					padding-right: 10px;
					font-weight: bold;
					font-family: Arial Unicode, Verdana, Arial, Helvetica, sans-serif;
				}
				.head2 table tr td{
					line-height: 18px;
					font-weight: bold;
					font-family: Arial Unicode, Verdana, Arial, Helvetica, sans-serif;
				}
				.head3 {
					position: relative;
					margin-bottom: 3px;
					min-height: 40px;
					line-height: 40px;
					padding-left: 10px;
					padding-right: 10px;
					font-size: 21px;
					font-weight: bold;
					font-family: Arial Unicode, Verdana, Arial, Helvetica, sans-serif;
				}
				.head3 table tr td{
					line-height: 35px;
					font-size: 21px;
					font-weight: bold;
					font-family: Arial Unicode, Verdana, Arial, Helvetica, sans-serif;
				}
				.head3Extreme{
					background-color: #FF6666;
				}
				.head3Severe{
					background-color: #FFCC33;
				}
				.head3Moderate{
					background-color: #FFFF66;
				}
				.head3Minor{
					background-color: #99FF99;
				}
				.head3Unknown{
					background-color: grey;
				}
				.main {
					background-color:#343D46;
					color:#FFFFFF;
					font-size: 16px;
					font-family:Verdana, Arial, Helvetica, sans-serif;
					text-shadow:none;
					padding-top: 10px;
					padding-bottom: 10px;
					padding-left: 10px;
					margin-bottom: 3px;
				}
				.main table tr td{
					color:#FFFFFF;
					font-size: 16px;
					font-family:Verdana, Arial, Helvetica, sans-serif;
					text-shadow:none;
				}
				.main2 {
					background-color:#343D46;
					color:#FFFFFF;
					font-family:Verdana, Arial, Helvetica, sans-serif;
					text-shadow:none;
					padding-top: 10px;
					padding-bottom: 10px;
					padding-left: 10px;
					margin-bottom: 3px;
				}
				.centerd {
					margin: 0;
					position: absolute;
					top: 50%;
					left: 50%;
					margin-right: -50%;
					transform: translate(-50%, -50%)
				}
				.left {
					margin: 0;
					position: absolute;
					left: 0%;
					top: 50%;
					transform: translate(0%, -50%);
					padding-left: 10px;
				}
				.right {
					margin: 0;
					position: absolute;
					right: 0%;
					top: 50%;
					transform: translate(0%, -50%);
					padding-right: 10px;
				}
			</style>
			<div class="container">
				<div class="head1">
					Common Alerting Protocol Version 1.2
				</div>
				<div class="head2 head3{cap:info/cap:severity}">
					<table style="width: 100%;">
						<tr>
							<td style="text-align: left; width:33.333%; word-break: break-word;">
								<xsl:call-template name="formatDate"><xsl:with-param name="date" select="cap:sent"/></xsl:call-template>
							</td>
							<td style="text-align: center; width:33.333%; word-break: break-word;"><xsl:value-of select="cap:identifier"/></td>
							<td style="text-align: right; width:33.333%;"><a href="mailto:{cap:sender}"><xsl:value-of select="cap:sender"/></a></td>
						</tr>
					</table>
				</div>
				<div class="head3 head3{cap:info/cap:severity}">
					<table style="width: 100%;">
						<tr>
							<td style="text-align: left; width:33.333%;"><xsl:value-of name="subcaption" select="cap:info/cap:headline"/></td>
							<td style="text-align: center; width:33.333%;"><xsl:value-of select="cap:info/cap:event"/></td>
							<td style="text-align: right; width:33.333%;"></td>
						</tr>
					</table>
				</div>

				<xsl:if test="cap:info/cap:effective != '' or cap:info/cap:onset != '' or cap:info/cap:expires != ''">
					<div class="main">
						<table style="width: 100%;">
							<xsl:if test="cap:info/cap:effective != ''">
								<tr>
									<td style="width: 4%;">Effective: </td>
									<td><xsl:call-template name="formatDate"><xsl:with-param name="date" select="cap:info/cap:effective"/></xsl:call-template></td>
								</tr>
							</xsl:if>
							<xsl:if test="cap:info/cap:onset != ''">
								<tr>
								<td style="width: 4%;">Onset: </td>
								<td><xsl:call-template name="formatDate"><xsl:with-param name="date" select="cap:info/cap:onset"/></xsl:call-template></td>
								</tr>
							</xsl:if>
							<xsl:if test="cap:info/cap:expires != ''">
								<tr>
								<td style="width: 4%;">Expires: </td>
								<td><xsl:call-template name="formatDate"><xsl:with-param name="date" select="cap:info/cap:expires"/></xsl:call-template></td>
								</tr>
							</xsl:if>
						</table>
					</div>
				</xsl:if>

				<xsl:for-each select="cap:info">
					<xsl:if test="cap:description != ''">
						<div class="main">
							Description: <xsl:if test="cap:language != ''">(<xsl:value-of select="cap:language"/>)</xsl:if><p/>
							<span style="color:yellow;">
								<xsl:value-of select="cap:description"/>
							</span>
						</div>
					</xsl:if>
					<xsl:if test="cap:instruction != ''">
						<div class="main">
							Instruction: <xsl:if test="cap:language != ''">(<xsl:value-of select="cap:language"/>)</xsl:if><p/>
							<xsl:value-of select="cap:instruction"/>
						</div>
					</xsl:if>
				</xsl:for-each>
				<div class="main">
					<table style="width: 100%;">
						<tr>
							<td style="width: 4%;">Certainty: </td>
							<td><xsl:value-of select="cap:info/cap:certainty"/></td>
						</tr>
						<tr>
							<td style="width: 4%;">Urgency: </td>
							<td><xsl:value-of select="cap:info/cap:urgency"/></td>
						</tr>
						<tr>
							<td style="width: 4%;">Severity: </td>
							<td><xsl:value-of select="cap:info/cap:severity"/></td>
						</tr>
					</table>
				</div>
				<div class="main">
						<xsl:value-of select="cap:info/cap:area/cap:areaDesc"/>
						<p/>
						<xsl:if test="cap:info/cap:area/cap:polygon != ''">
							Polygon: <pre><xsl:value-of select="cap:info/cap:area/cap:polygon"/></pre>
							<p/>
						</xsl:if>
						<xsl:if test="cap:info/cap:area/cap:circle != ''">
							Circle: <pre><xsl:value-of select="cap:info/cap:area/cap:circle"/></pre>
							<p/>
						</xsl:if>
						<xsl:for-each select="cap:info/cap:area/cap:geocode">
							Type: <xsl:value-of select="cap:valueName"/>
							<br/>
							Code: <xsl:value-of select="cap:value"/>
							<p/>
						</xsl:for-each>
						<xsl:if test="cap:info/cap:area/cap:altitude != ''">
							Altitude: <pre><xsl:value-of select="cap:info/cap:area/cap:altitude"/></pre>
							<p/>
						</xsl:if>
						<xsl:if test="cap:info/cap:area/cap:ceiling != ''">
							Ceiling: <pre><xsl:value-of select="cap:info/cap:area/cap:ceiling"/></pre>
						</xsl:if>
				</div>
				<div class="main2">

					<!--
					identifier: <div style="padding-left:10px;"><xsl:value-of select="cap:identifier"/></div>
					<br/>
					sender: <div style="padding-left:10px;"><xsl:value-of select="cap:sender"/></div>
					<br/>
					sent: <div style="padding-left:10px;"><xsl:value-of select="cap:sent"/></div>
					<br/>
					-->
					<!-- 
						“Actual” - Actionable by all targeted recipients
						“Exercise” - Actionable only by designated exercise participants; exercise identifier SHOULD appear in <note>
						“System” - For messages that support alert network internal functions
						“Test” - Technical testing only, all recipients disregard
						“Draft” – A preliminary template or draft, not actionable in its current form
					-->
					status: <div style="padding-left:10px;"><xsl:value-of select="cap:status"/></div>
					<br/>
					msgType: <div style="padding-left:10px;"><xsl:value-of select="cap:msgType"/></div>
					<br/>
					source: <div style="padding-left:10px;"><xsl:value-of select="cap:source"/></div>
					<br/>
					scope: <div style="padding-left:10px;"><xsl:value-of select="cap:scope"/></div>
					<br/>
					restriction: <div style="padding-left:10px;"><xsl:value-of select="cap:restriction"/></div>
					<br/>
					addresses: <div style="padding-left:10px;"><xsl:value-of select="cap:addresses"/></div>
					<br/>
					code: <div style="padding-left:10px;"><xsl:value-of select="cap:code"/></div>
					<br/>
					note: <div style="padding-left:10px;"><xsl:value-of select="cap:note"/></div>
					<br/>
					references: <div style="padding-left:10px;"><xsl:value-of select="cap:references"/></div>
					<br/>
					incidents: <div style="padding-left:10px;"><xsl:value-of select="cap:incidents"/></div>
					<br/>

					info: <div style="padding-left:10px;"><xsl:for-each select="cap:info">
						<br/>
						language: <div style="padding-left:10px;"><xsl:value-of select="cap:language"/></div>
						<br/>
						category: <div style="padding-left:10px;"><xsl:value-of select="cap:category"/></div>
						<br/>
						event: <div style="padding-left:10px;"><xsl:value-of select="cap:event"/></div>
						<br/>
						responseType: <div style="padding-left:10px;"><xsl:value-of select="cap:responseType"/></div>
						<br/>
						urgency: <div style="padding-left:10px;"><xsl:value-of select="cap:urgency"/></div>
						<br/>
						severity: <div style="padding-left:10px;"><xsl:value-of select="cap:severity"/></div>
						<br/>
						certainty: <div style="padding-left:10px;"><xsl:value-of select="cap:certainty"/></div>
						<br/>
						audience: <div style="padding-left:10px;"><xsl:value-of select="cap:audience"/></div>
						<br/>
						eventCode: <div style="padding-left:10px;"><xsl:for-each select="cap:eventCode">
							valueName: <div style="padding-left:10px;"><xsl:value-of select="cap:valueName"/></div>
							<br/>
							value: <div style="padding-left:10px;"><xsl:value-of select="cap:value"/></div>
							<br/>
						</xsl:for-each></div>
						effective: <div style="padding-left:10px;"><xsl:value-of select="cap:effective"/></div>
						<br/>
						onset: <div style="padding-left:10px;"><xsl:value-of select="cap:onset"/></div>
						<br/>
						expires: <div style="padding-left:10px;"><xsl:value-of select="cap:expires"/></div>
						<br/>
						senderName: <div style="padding-left:10px;"><xsl:value-of select="cap:senderName"/></div>
						<br/>
						headline: <div style="padding-left:10px;"><xsl:value-of select="cap:headline"/></div>
						<br/>
						description: <div style="padding-left:10px;"><xsl:value-of select="cap:description"/></div>
						<br/>
						instruction: <div style="padding-left:10px;"><xsl:value-of select="cap:instruction"/></div>
						<br/>
						web: <div style="padding-left:10px;"><xsl:value-of select="cap:web"/></div>
						<br/>
						contact: <div style="padding-left:10px;"><xsl:value-of select="cap:contact"/></div>
						<br/>
						parameter: <div style="padding-left:10px;"><xsl:for-each select="cap:parameter">
							valueName: <div style="padding-left:10px;"><xsl:value-of select="cap:valueName"/></div>
							<br/>
							value: <div style="padding-left:10px;"><xsl:value-of select="cap:value"/></div>
							<br/>
						</xsl:for-each></div>
						resource: <div style="padding-left:10px;"><xsl:for-each select="cap:resource">
							<br/>
							resourceDesc: <div style="padding-left:10px;"><xsl:value-of select="cap:resourceDesc"/></div>
							<br/>
							mimeType: <div style="padding-left:10px;"><xsl:value-of select="cap:mimeType"/></div>
							<br/>
							size: <div style="padding-left:10px;"><xsl:value-of select="cap:size"/></div>
							<br/>
							uri: <div style="padding-left:10px;"><xsl:value-of select="cap:uri"/></div>
							<br/>
							derefUri: <div style="padding-left:10px;"><xsl:value-of select="cap:derefUri"/></div>
							<br/>
							digest: <div style="padding-left:10px;"><xsl:value-of select="cap:digest"/></div>
							<br/>
						</xsl:for-each></div>
						area: <div style="padding-left:10px;"><xsl:for-each select="cap:area">

							areaDesc: <div style="padding-left:10px;"><xsl:value-of select="cap:areaDesc"/></div>
							<br/>
							polygon: <div style="padding-left:10px;"><xsl:value-of select="cap:polygon"/></div>
							<br/>
							circle: <div style="padding-left:10px;"><xsl:value-of select="cap:circle"/></div>
							<br/>
							geocode: <div style="padding-left:10px;"><xsl:for-each select="cap:geocode">
								valueName: <div style="padding-left:10px;"><xsl:value-of select="cap:valueName"/></div>
								<br/>
								value: <div style="padding-left:10px;"><xsl:value-of select="cap:value"/></div>
								<br/>
							</xsl:for-each></div>
							altitude: <div style="padding-left:10px;"><xsl:value-of select="cap:altitude"/></div>
							<br/>
							ceiling: <div style="padding-left:10px;"><xsl:value-of select="cap:ceiling"/></div>
							<br/>
						</xsl:for-each></div>
					</xsl:for-each></div>
				</div>
			</div>
		</body>
	</html>
	</xsl:template>

	<xsl:template name="formatDate">
		<xsl:param name="date"/>
		<!-- org. YYYY-MM-DDTHH:ii:ss+UTC0 -->
		<xsl:param name="y" select="substring($date, 1, 4 )"/>
		<xsl:param name="m" select="substring($date, 6, 2 )"/>
		<xsl:param name="d" select="substring($date, 9, 2 )"/>
		<xsl:param name="H" select="substring($date, 12, 2 )"/>
		<xsl:param name="i" select="substring($date, 15, 2 )"/>
		<xsl:param name="s" select="substring($date, 18, 2 )"/>
		<xsl:param name="UTC" select="substring($date, 21, 5 )"/>
		<xsl:value-of select="concat($y, '.', $m, '.', $d, ' ', $H, ':', $i, ':', $s, ' +', $UTC)" />
	</xsl:template>
</xsl:stylesheet>

<!--
oasis cap v1.2:

alert

	identifier
	sender
	sent
	status
	msgType
	source
	scope
	restriction
	addresses
	code
	note
	references
	incidents

	info
		language
		category
		event
		responseType
		urgency
		severity
		certainty
		audience
		eventCode
			valueName
			value

		effective
		onset
		expires
		senderName
		headline
		description
		instruction
		web
		contact
		parameter
			valueName
			value

		resource
			resourceDesc
			mimeType
			size
			uri
			derefUri
			digest

		area

			areaDesc
			polygon
			circle
			geocode
				valueName
				value

			altitude
			ceiling
-->

