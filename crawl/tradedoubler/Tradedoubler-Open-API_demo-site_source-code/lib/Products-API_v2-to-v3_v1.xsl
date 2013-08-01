<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:cm="urn:com:tradedoubler:pf:model:xml:common" exclude-result-prefixes="xs xsi xsl" xmlns="urn:com:tradedoubler:pf:model:xml:input">
	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<products>
			<xsl:variable name="var1_instance" select="."/>
			<xsl:for-each select="$var1_instance/productFeed/product">
				<xsl:variable name="var2_productFeed" select="."/>
				<xsl:attribute name="version">
					<xsl:value-of select="'3.0'"/>
				</xsl:attribute>
				<product>
					<xsl:attribute name="sourceproductid">
						<xsl:value-of select="string(@id)"/>
					</xsl:attribute>
					<cm:name>
						<xsl:value-of select="string(name)"/>
					</cm:name>
					<cm:description>
						<xsl:value-of select="string(description)"/>
					</cm:description>
					<cm:producturl>
						<xsl:value-of select="string(productURL)"/>
					</cm:producturl>
					<cm:imageurl>
						<xsl:value-of select="string(imageURL)"/>
					</cm:imageurl>
					<cm:price>
						<xsl:value-of select="number(string(price))"/>
					</cm:price>
					<cm:categories>
						<xsl:for-each select="categories/category">
							<cm:category>
								<xsl:attribute name="name">
									<xsl:value-of select="@name"/>
								</xsl:attribute>
								<xsl:if test="@tdCategoryId">
									<xsl:attribute name="tdCategoryId">
										<xsl:value-of select="@tdCategoryId"/>
									</xsl:attribute>
								</xsl:if>
							</cm:category>
						</xsl:for-each>
					</cm:categories>
					<cm:fields>
						<xsl:for-each select="fields/field">
							<cm:field>
								<xsl:attribute name="name">
									<xsl:value-of select="string(@name)"/>
								</xsl:attribute>
								<xsl:value-of select="string(@value)"/>
							</cm:field>
						</xsl:for-each>
						<xsl:if test="not(fields/field)">
							<cm:field>
								<xsl:attribute name="name">
									<xsl:value-of select="'fields'"/>
								</xsl:attribute>
								<xsl:value-of select="'none'"/>
							</cm:field>
						</xsl:if>
					</cm:fields>
					<xsl:if test="shippingcost and not(shippingcost[.=''])">
						<cm:shippingcost>
							<xsl:value-of select="string(shippingCost)"/>
						</cm:shippingcost>
					</xsl:if>
					<xsl:if test="shortdescription and not(shortdescription[.=''])">
						<cm:shortdescription>
							<xsl:value-of select="string(shortDescription)"/>
						</cm:shortdescription>
					</xsl:if>
					<xsl:if test="promotext and not(promotext[.=''])">
						<cm:promotext>
							<xsl:value-of select="string(promoText)"/>
						</cm:promotext>
					</xsl:if>
					<xsl:if test="warranty and not(warranty[.=''])">
						<cm:warranty>
							<xsl:value-of select="string(warranty)"/>
						</cm:warranty>
					</xsl:if>
					<xsl:if test="instock and not(instock[.=''])">
						<cm:instock>
							<xsl:value-of select="number(string(inStock))"/>
						</cm:instock>
					</xsl:if>
					<xsl:if test="availability and not(availability[.=''])">
						<cm:availability>
							<xsl:value-of select="string(availability)"/>
						</cm:availability>
					</xsl:if>
					<xsl:if test="deliverytime and not(deliverytime[.=''])">
						<cm:deliverytime>
							<xsl:value-of select="string(deliveryTime)"/>
						</cm:deliverytime>
					</xsl:if>
					<xsl:if test="condition and not(condition[.=''])">
						<cm:condition>
							<xsl:value-of select="string(condition)"/>
						</cm:condition>
					</xsl:if>
					<xsl:if test="weight and not(weight[.=''])">
						<cm:weight>
							<xsl:value-of select="string(weight)"/>
						</cm:weight>
					</xsl:if>
					<xsl:if test="size and not(size[.=''])">
						<cm:size>
							<xsl:value-of select="string(size)"/>
						</cm:size>
					</xsl:if>
					<xsl:if test="model and not(model[.=''])">
						<cm:model>
							<xsl:value-of select="string(model)"/>
						</cm:model>
					</xsl:if>
					<xsl:if test="brand and not(brand[.=''])">
						<cm:brand>
							<xsl:value-of select="string(brand)"/>
						</cm:brand>
					</xsl:if>
					<xsl:if test="manufacturer and not(manufacturer[.=''])">
						<cm:manufacturer>
							<xsl:value-of select="string(manufacturer)"/>
						</cm:manufacturer>
					</xsl:if>
					<xsl:if test="tech_specs and not(tech_specs[.=''])">
						<cm:techspecs>
							<xsl:value-of select="string(tech_specs)"/>
						</cm:techspecs>
					</xsl:if>
					<xsl:if test="ean and not(ean[.=''])">
						<cm:ean>
							<xsl:value-of select="string(ean)"/>
						</cm:ean>
					</xsl:if>
					<xsl:if test="upc and not(upc[.=''])">
						<cm:upc>
							<xsl:value-of select="string(upc)"/>
						</cm:upc>
					</xsl:if>
					<xsl:if test="isbn and not(isbn[.=''])">
						<cm:isbn>
							<xsl:value-of select="string(isbn)"/>
						</cm:isbn>
					</xsl:if>
					<xsl:if test="mpn and not(mpn[.=''])">
						<cm:mpn>
							<xsl:value-of select="string(mpn)"/>
						</cm:mpn>
					</xsl:if>
					<xsl:if test="sku and not(sku[.=''])">
						<cm:sku>
							<xsl:value-of select="string(sku)"/>
						</cm:sku>
					</xsl:if>
				</product>
			</xsl:for-each>
		</products>
	</xsl:template>
</xsl:stylesheet>