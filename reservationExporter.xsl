<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:param name="serviceFilter" />

    <xsl:template match="/">
        <html>
            <head>
                <title>Records</title>
            </head>
            <body>
                <xsl:apply-templates select="records/record[service=$serviceFilter or $serviceFilter = '']" />
            </body>
        </html>
    </xsl:template>

    <xsl:template match="record">
        <tr>
            <td><xsl:value-of select="@id" /></td>
            <td><xsl:value-of select="phoneNumber" /></td>
            <td><xsl:value-of select="name" /></td>
            <td><xsl:value-of select="time" /></td>
            <td><xsl:value-of select="service" /></td>
            <td><xsl:value-of select="carNumber" /></td>
            <td>
                <form method="post" action="reservations.php">
                    <input type="hidden" name="delete_id" value="{@id}"/>
                    <input type="submit" name="delete" value="Kustuta"/>
                </form>
            </td>
        </tr>
    </xsl:template>
</xsl:stylesheet>
