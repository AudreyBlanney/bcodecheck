<?php
    $issues_array = array(
        '可能的跨站脚本 '=> array(
            'link' => '(1）输入验证：对用户的输入进行合理验证（如：字母、数字）。<br>
                    （2）输出编码：根据数据将要置于HTML上下文中的不同位置（Html标签、Html属性、JavaScript脚本、CSS、URL），对所有不可信数据进行恰当的输出编码。例如，在Html标签或者Html属性中输出不可信的数据，可以采用htmlentities() 和 htmlspecialchars()进行HtmlEncode。<br>
                    （3）设置HttpOnly属性，浏览器将禁止页面的JavaScript访问带有HttpOnly属性的Cookie，从而避免攻击者利用跨站脚本漏洞进行Cookie劫持攻击。给Cookie添加HttpOnly的代码如下。<br>
                    在PHP4中：header("Set-Cookie: hidden=value; httpOnly");<br>
                    在PHP5中：setcookie("abc", "test", NULL, NULL, NULL, NULL, TRUE);最后一个参数是HttpOnly属性<br>
                    &https://www.owasp.org/index.php/XSS."",'
        ),
        '可能的SQL注入' => array(
            'link' => '（1）输入验证：对用户的输入进行合理验证（字母、数字）。<br>
                    （2）正确使用预编译SQL语句，绑定变量。例如，可以将描述例子改写为使用参数化 SQL 指令：<br>
                    $mysqli = new mysqli($host,$user, $password, $db);<br>
                    $name = $_POST["name"];<br>
                    $query = "SELECT * FROM tbl_users WHERE userLogin = ?";<br>
                    $stmt = $mysqli->prepare($query);<br>
                    $stmt->bind_param("s",$name);<br>
                    $stmt->execute();<br>
                    &https://www.owasp.org/index.php/SQL_Injection.",'
        ),
        '可能的HTTP头注入' => array(
            'link' => '更新PHP以防止标头注入或实施白名单。 另外删除控制字符，如％00，％0D，％0A等。<br>
                     代码层面常见的解决方案：<br>
                    1.严格检查变量是否已经初始化。<br>
                    2.禁止header()函数中的参数外界可控。<br>
                    &https://www.owasp.org/index.php/HTTP_Response_Splitting.",'
        ),
        '可能的HTTP响应拆分' => array(
            'link' => '更新PHP以防止标头注入或实施白名单。 另外删除控制字符，如％00，％0D，％0A等。<br>
                    1.进入theme文件夹，找到当前主题中的functions.php文件。<br>
                    2.在functions文件中快速搜索“$redirect”找到“$redirect = $_GET["r"];”这段代码。<br>
                    3.将以下代码替换步骤2中的代码，就可以搞定，代码如下：$redirect = trim(str_replace("\r","",str_replace("\r\n","",strip_tags(str_replace(" ","","",str_replace("\n", "", str_replace(" ","",str_replace("\t","",trim($redirect))))),""))));<br>
                    &https://www.owasp.org/index.php/HTTP_Response_Splitting."'
        ),
        '可能执行任意代码' => array(
            'link' => '1.使用json保存数组，当读取时就不需要使用eval了<br>
                    2.对于必须使用eval的地方，一定严格处理用户数据<br>
                    3.字符串使用单引号包括可控代码，插入前使用addslashes转义<br>
                    4.放弃使用preg_replace的e修饰符，使用preg_replace_callback()替换<br>
                    5.若必须使用preg_replace的e修饰符，则必用单引号包裹正则匹配出的对象<br>
                    &https://www.owasp.org/index.php/Code_Injection. ",'
        ),
        '可能的远程文件包含' => array(
            'link' => '1.严格检查变量是否已经初始化。<br>
                    2.建议假定所有输入都是可疑的，尝试对所有输入提交可能可能包含的文件地址，包括服务器本地文件及远程文件，进行严格的检查，参数中不允许出现../之类的目录跳转符。<br>
                    3.严格检查include类的文件包含函数中的参数是否外界可控。<br>
                    4.不要仅仅在客户端做数据的验证与过滤，关键的过滤步骤在服务端进行。<br>
                    5.在发布应用程序之前测试所有已知的威胁。<br>
                    &http://websec.wordpress.com/2010/02/22/exploiting-php-file-inclusion-overview/",'
        ),
        '可能的服务器端命令执行' => array(
            'link' => '1.建议假定所有输入都是可疑的，尝试对所有输入提交可能执行命令的构造语句进行严格的检查或者控制外部输入，系统命令执行函数的参数不允许外部传递。<br>
                    2.不仅要验证数据的类型，还要验证其格式、长度、范围和内容。<br>
                    3.不要仅仅在客户端做数据的验证与过滤，关键的过滤步骤在服务端进行。<br>
                    4.对输出的数据也要检查，数据库里的值有可能会在一个大网站的多处都有输出，即使在输入做了编码等操作，在各处的输出点时也要进行安全检查。<br>
                    5.在发布应用程序之前测试所有已知的威胁。<br>
                    &http://websec.wordpress.com/2010/02/22/exploiting-php-file-inclusion-overview/",'
        ),
        '可能危险的文件操作' => array(
            'link' => '1.为文件名建立白名单并将文件名限制为特定路径或扩展名。<br>
                    2.写入PHP文件，确保攻击者无法编写自己的PHP代码。<br>
                    3.使用数组或正则表达式的白名单（例如仅限英文数字）。<br>
                    &http://projects.webappsec.org/w/page/13246932/Improper Filesystem Permissions",'
        ),
        '可能XPath注入' => array(
            'link' => '1.数据提交到服务器上端，在服务端正式处理这批数据之前，对提交数据的合法性进行验证。<br>
                    2.检查提交的数据是否包含特殊字符，对特殊字符进行编码转换或替换、删除敏感字符或字符串。<br>
                    3.对于系统出现的错误信息，以IE错误编码信息替换，屏蔽系统本身的出错信息。<br>
                    4.参数化XPath查询，将需要构建的XPath查询表达式，以变量的形式表示，变量不是可以执行的脚本。如下代码可以通过创建保存查询的外部文件使查询参数化：<br>
                     declare variable $loginID as xs：string external；<br>
                     declare variable $password as xs：string external；<br>
                     //users/user[@loginID=$loginID and@password= $password]<br>
                    5.通过MD5、SSL等加密算法，对于数据敏感信息和在数据传输过程中加密，即使某些非法用户通过非法手法获取数据包，看到的也是加密后的信息。<br>
                    &http://packetstormsecurity.org/files/view/33380/Blind_XPath_Injection_20040518.pdf",'
        ),
        '可能LDAP注入' => array(
            'link' => '1.圆括号、星号、逻辑操作符、关系运操作符在应用层都必须过滤。<br>
                    2.构造LDAP搜索过滤器的值在发送给LDAP服务器查询之前都要用应用层有效地值列表来核对。<br>
                    3.替换正则表达式。<br>
                    &http://www.blackhat.com/presentations/bh-europe-08/Alonso-Parada/Whitepaper/bh-eu-08-alonso-parada-WP.pdf",'
        ),
        '可能有风险的反序列化（）的用法' => array(
            'link' => '一、漏洞解决方法:<br>
                  1.防止使用unserialize，因为它包含更多缺陷。<br>
                  2.使用 SerialKiller 替换进行序列化操作的 ObjectInputStream 类；<br>
                  3.在不影响业务的情况下，临时删除掉项目里的“org/apache/commons/collections/functors/InvokerTransformer.class” 文件；<br>
                    在服务器上找org/apache/commons/collections/functors/InvokerTransformer.class类的jar，目前weblogic10以后都在Oracle/Middleware/modules下com.bea.core.apache.commons.collections_3.2.0.jar，创建临时目录tt，解压之后删除InvokerTransformer.class类后再打成com.bea.core.apache.commons.collections_3.2.0.jar覆盖Oracle/Middleware/modules下，重启所有服务。如下步骤是linux详细操作方法：<br>
                    A)mkdir tt<br>
                    B)cp -r Oracle/Middleware/modules/com.bea.core.apache.commons.collections_3.2.0.jar ./tt<br>
                    C)jar xf Oracle/Middleware/modules/com.bea.core.apache.commons.collections_3.2.0.jar<br>
                    D)cd org/apache/commons/collections/functors<br>
                    E)rm -rf InvokerTransformer.class<br>
                    F)jar cf com.bea.core.apache.commons.collections_3.2.0.jar org/* META-INF/*<br>
                    G)mv com.bea.core.apache.commons.collections_3.2.0.jar Oracle/Middleware/modules/<br>
                    H)重启服务<br>
               二、漏洞解决方法:<br>
                  1.假如不是处理weblogic自带的com.bea.core.apache.commons.collections_3.2.0.jar，而是修改应用代码collections_*.jar，一定在发版本不能覆盖。应用覆盖、备份恢复的时候和发版本的时候也请切记不要覆盖掉修改后的JAR文件。<br>
                  2.重启服务时候要删除server-name下的cache和tmp<br>
                   例如<br>
                    rm -rf  ~/user_projects/domains/base_domain/servers/AdminServer/cache<br>
                    rm -rf  ~/user_projects/domains/base_domain/servers/AdminServer/tmp<br>
                    &https://media.blackhat.com/bh-us-10/presentations/Esser/BlackHat-USA-2010-Esser-Utilizing-Code-Reuse-Or-Return-Oriented-Programming-In-PHP-Application-Exploits-slides.pdf",
        '
        ),
        '可能有风险的DBMS操作' => array(
            'link' => ' 1.请确保在没有正确清理的情况下，不会将不可信任的用户输入传递给DBMS服务器。<br>
                      2.始终将预期的字符串嵌入引号，并将其嵌入到查询中之前使用PHP buildin函数转义该字符串。<br>
                      3.始终嵌入无引号的预期整数，并将数据类型转换为整数，然后再将其嵌入到查询中。<br>
                      4.转义数据但不包括引号嵌入数据是不安全的。<br>
                   &https://www.owasp.org/index.php/SQL_Injection",'
        ),
        '可能不被信任的文件系统输入' => array(
            'link' => '基于上下文使用适当的转义/编码。N/A'
        ),
        '可能不被信任$ _SERVER变量输入' => array(
            'link' => '加强程序自身的过滤机制,基于上下文使用适当的转义/编码。N/A'
        ),
        '可能不被信任的用户输入' => array(
            'link' => '1.设置白名单.2.基于上下文使用适当的转义/编码.N/A'
        ),
        '从数据库中可能有风险输入' => array(
            'link' => '基于上下文使用适当的转义/编码。N/A'
        ),
        '不安全的密码' => array(
            'link' => '根据情况，通过使用正确的API来保持最高的加密标准。N/A'
        ),
        '不安全的密码管理（侵犯隐私权）' => array(
            'link' => '始终确保为收集敏感信息的任何API正确设置相应的标志，以便敏感信息在输入提示符下输入时不会回传给用户'
        ),
        '潜在的命令注入' => array(
            'link' => '1.总是试图避免任何用户数据达到api控制,做一些命令执行. 2.确保你有正确转义所有有害的字符.  https://www.owasp.org/index.php/Command_Injection'
        ),
        '潜在的XSS' => array(
            "link" => "1.对XSS的最佳防御是上下文敏感的输出编码,通常有4个要考虑的上下文：HTML，JavaScript，CSS（样式）和URL. 2.遵守XSS保护规则,在OWASP XSS防范作弊表中定义. 3.检查是否正在使用Spring框架.  https://www.owasp.org/index.php/XSS_%28Cross_Site_Scripting%29_Prevention_Cheat_Sheet",
        ),

        "潜在的LDAP注入" => array(
            "link" => "1.针对LDAP注入的主要防御措施是强大的输入验证.2.将任何不受信任的数据包含在LDAP查询中.3.检查它是否导入javax.naming.directory.InitialDirContext.4.检查它是否初始化新的SearchControls（）.5.检查它是否初始化新的InitialDirContext（）http://www.veracode.com/security/ldap-injection",
        ),
        "潜在的LDAP注入" => array(
            "link"=>"1.针对LDAP注入的主要防御措施是强大的输入验证.2.将任何不受信任的数据包含在LDAP查询中.3.检查它是否导入javax.naming.directory.InitialDirContext.4.检查它是否初始化新的SearchControls（）.5.检查它是否初始化新的InitialDirContext（）.  http://www.veracode.com/security/ldap-injection",
        ),

        "潜在的未经验证的重定向" => array(
            "link" => "1.不接受用户重定向目的地; 接受目的地密钥，并使用它来查找目标（合法）. 2.检查它是否导入javax.servlet.http.HttpServlet（Request | Response）. 3.检查它是否设置HTTP重定向状态代码.  https://www.owasp.org/index.php/Top_10_2013-A10-Unvalidated_Redirects_and_Forwards",
        ),
        "使用脚本引擎时，潜在的代码注入" => array(
            "link" => "1.对代码构建进行仔细分析. 2.检查它是否导入javax.script.ScriptEngine（Manager）.  http://codeutopia.net/blog/2009/01/02/sandboxing-rhino-in-java/",
        ),
        "潜在的表达式语言注入" => array(
            "link" => "1.对代码构建进行仔细分析. 2.检查它是否导入org.springframework.expression.  https://www.mindedsecurity.com/fileshare/ExpressionLanguageInjection.pdf",
        ),
        "潜在的SQL/HQL注入" => array(
            "link" => "1.使用参数化查询/指定变量来防止SQL注入. 2.检查它是否导入org.hibernate.  https://www.mindedsecurity.com/fileshare/ExpressionLanguageInjection.pdf",
        ),
        "潜在的SQL/JDOQL注入（JDO）" => array(
            "link" =>  "1.SQL查询中包含的输入值需要安全传递,在查询语句中指定变量可以防止SQL注入的风险. 2.检查它是否导入javax.jdo.  https://www.owasp.org/index.php/Query_Parameterization_Cheat_Sheet",
        ),
        "潜在的SQL/JPQL注入（JPA）" => array(
            "link" => "1.SQL查询中包含的输入值需要安全传递.在查询语句中指定变量可以防止SQL注入的风险。 2.检查它是否导入javax.persistence.  https://www.owasp.org/index.php/Query_Parameterization_Cheat_Sheet",
        ),
        "潜在的XSS" => array(
            "link" =>  "1.对XSS的最佳防御是上下文敏感的输出编码，通常有4个要考虑的上下文：HTML，JavaScript，CSS（样式）和URL. 2.遵守XSS保护规则,在OWASP XSS防范作弊表中定义. 3.检查是否正在使用Spring框架.  https://www.owasp.org/index.php/XSS_%28Cross_Site_Scripting%29_Prevention_Cheat_Sheet",
        ),
        "潜在的XPath注入" => array(
            "link" => "1.数据提交到服务器上端，在服务端正式处理这批数据之前，对提交数据的合法性进行验证。2.检查提交的数据是否包含特殊字符，对特殊字符进行编码转换或替换、删除敏感字符或字符串。3.对于系统出现的错误信息，以IE错误编码信息替换，屏蔽系统本身的出错信息。4.参数化XPath查询，将需要构建的XPath查询表达式，以变量的形式表示，变量不是可以执行的脚本。如下代码可以通过创建保存查询的外部文件使查询参数化：<br>
                    declare variable ".'{$loginID}'." as xs：string external；<br>
                    declare variable ".'{$password}'." as xs：string external；<br>
                    //users/user[@loginID=".'{$loginID}'." and@password= ".'{$password}'."] 5.通过MD5、SSL等加密算法，对于数据敏感信息和在数据传输过程中加密，即使某些非法用户通过非法手法获取数据包，看到的也是加密后的信息。6.检查它是否导入org.apache.xpath.XPath（API）.  https://www.securecoding.cert.org/confluence/pages/viewpage.action?pageId=61407250",
        ),
        "XML解析易受XXE" => array(
            "link" => "1.开发语言禁用外部实体.  2.过滤用户提交的XML数据. 3.检查它是否导入org.xml.sax.XMLReader或javax.xml.parsers.DocumentBuilder或javax.xml.parsers.SAXParser.  https://www.securecoding.cert.org/confluence/pages/viewpage.action?pageId=61702260",
        ),

        "在Cookie潜在的敏感数据" => array(
            "link" => "1.自定义Cookie用于比特定会话持续更长时间的信息.2.检查它是否导入javax.servlet.http.Cookie或javax.servlet.http.HttpServlet; 或javax.servlet.http.HttpServletRequest;或javax.servlet.http.HttpServletResponse.  http://cwe.mitre.org/data/definitions/315.html",
        ),
        "REST端点检测" => array(
            "link" => "1.认证，如果强制执行，则应进行测试。2.访问控制，如果被强制执行，应该进行测试。3.应该跟踪输入的潜在漏洞。4.理想情况下应该通过SSL通信。5.如果服务支持写入（例如通过POST），应对CSRF的脆弱性进行调查。6.检查它是否导入javax.ws.rs.Path;  https://www.owasp.org/index.php/REST_Assessment_Cheat_Sheet",
        ),

        "SOAP端点检测" => array(
            "link" => "分析此Web服务的安全性。 例如：1.认证，如果强制执行，则应进行测试. 2.访问控制，如果被强制执行，应该进行测试. 3.应该跟踪输入的潜在漏洞. 4.理想情况下应该通过SSL通信. 5.检查它是否导入javax.jws.Web（Method | Service）.  https://www.owasp.org/index.php/Web_Service_Security_Cheat_Sheet",
        ),
        "通过Servlet API的不可信的客户端数据使用" => array(
            "link" => "在将这些值传递给敏感API之前，您可能需要验证或清理这些值，例如：1.SQL查询（可能导致SQL注入）.2.文件打开（可能导致路径遍历）.3.命令执行（潜在命令注入）.4.HTML构造（潜在XSS）.5.检查它是否检查是否导入javax.servlet.http.HttpServlet;或javax.servlet.http.HttpServletRequest;或javax.servlet.http.HttpServletResponse.  http://cwe.mitre.org/data/definitions/20.html",
        ),
        "Spring MVC的端点检测" => array(
            "link" => "1.对暴露方式进行分析检测，以确保远程暴露方式是安全的. 2.检查是否检查是否导入org.springframework.web.bind.annotation.（PathVariable | RequestMapping | RequestMethod）.  http://cwe.mitre.org/data/definitions/20.html",
        ),
        "Struts的MVC V1检测端点" => array(
            "link" => " 1.对这些参数的使用进行检查，以确保安全使用. 2.检查是否导入org.apache.struts.action.（ActionForm | ActionForward | ActionMapping | Action）.  http://cwe.mitre.org/data/definitions/20.html",
        ),
        "Struts2的MVC端点检测"=> array(
            "link" => "对这些参数的使用进行检查，以确保安全使用.  http://cwe.mitre.org/data/definitions/20.html",
        ),
        "Wicket框架端点检测" => array(
            "link" => "1.检测应用程序中的每个Wicket页面，以确保所有的输入自动映射之前已被正确验证。2.检查它是否导入org.apache。  http://cwe.mitre.org/data/definitions/20.html",
        ),

        "不安全弱密码使用" => array(
            "link" =>  "用AES代替DES或3DES,密码c = Cipher.getInstance（'AES / GCM / NoPadding '）.  http://cwe.mitre.org/data/definitions/326.html",
        ),

        "API使用不安全ECB模式" => array(
            "link" =>  "使用像Galois / Counter Mode（GCM）这样的代码.  http://csrc.nist.gov/groups/ST/toolkit/BCM/modes_development.html#01",
        ),
        "密码算法是容易造成Oracle攻击" => array(
            "link" => "使用类似Galois / Counter Mode（GCM）与NoPadding的方法.  http://capec.mitre.org/data/definitions/463.html",
        ),
        "自定义消息摘要有风险" => array(
            "link" => "升级算法,使用足够安全并且足够强大的算法.  MessageDigest sha256Digest = .  MessageDigest.getInstance（SHA256）.  sha256Digest.update（password.getBytes（））.  检查它是否导入java.security.MessageDigest.  http://csrc.nist.gov/groups/ST/toolkit/secure_hashing.html",
        ),
        "不安全和不良的空密码使用" => array(
            "link" =>  "1.避免使用NullCipher， 其意外使用可能引起重大机密风险. 2.检查它是否导入javax.crypto.NullCipher.  http://csrc.nist.gov/groups/ST/toolkit/secure_hashing.html",
        ),
        "正在使用未加密套接字" => array(
            "link" => "为了确保安全通信始终使用SSL Socket，并且除了使用SSL套接字外，确保使用SSLSocketFactory进行所有相应的证书验证和检查，确保不会受到中间人攻击。检查它是否导入java.net.Socket.  https://www.owasp.org/index.php/Top_10_2010-A9",
        ),
        "正在使用弱消息摘要" => array(
            "link" => "1.NIST建议使用SHA-1，SHA-256，SHA-384，SHA-512，SHA-512/224或SHA-512/256. 2.检查它是否导入java.security.MessageDigest. 3.检查它是否导入org.apache.commons.codec.digest.DigestUtils.  http://csrc.nist.gov/groups/ST/toolkit/secure_hashing.html",
        ),
         "RSA不安全模式的使用" => array(
            "link" => "1.避免使用RSA而没有适当的填充。2.检查它是否导入javax.crypto.Ciphe.  http://rdist.root.org/2009/10/06/why-rsa-encryption-padding-is-critical/",
         ),
          "使用RSA弱密钥大小" => array(
              "link" =>  "1.KeyPairGenerator创建应至少具有2048位密钥大小.2.检查它是否是一个RSA实例：KeyPairGenerator.getInstance（RSA ）.3.检查它是否导入java.security.spec.RSAKeyGenParameterSpec.4.检查它是否导入java.security.KeyPairGenerator.5.检查它是否导入java.security.  http://www.emc.com/emc-plus/rsa-labs/standards-initiatives/how-large-a-key-should-be-used.htm",
          ),
          "弱密钥大小河豚用法" => array(
              "link" => "1.如果算法可以改变，应该使用AES块密码或至少将密钥生成值设置为大于等于128位.2.检查它是否导入javax.crypto.KeyGenerator.3.检查keygenerator.init大小是否> 128.4.检查keygenerator.generate（）是否被调用来生成密钥.  http://cwe.mitre.org/data/definitions/326.html",
          ),
          "Hazelcast对称加密" => array(
              "link" => "1.检查它是否导入com.hazelcast.config.SymmetricEncryptionConfig.2.检查它是否导入com.hazelcast.config.NetworkConfig.  http://projects.webappsec.org/w/page/13246945/Insufficient%20Transport%20Layer%20Protection",
          ),
          "OWASP ESAPI加密库的使用" => array(
              "link" => "1.更新ESAPI版本.2.检查它是否导入org.owasp.esapi.ESAPI.3.检查它是否导入org.owasp.esapi.crypto.CipherText.  http://lists.owasp.org/pipermail/esapi-dev/2015-March/002533.html",
          ),
          "地理位置API的使用" => array(
              "link" => "限制地理位置的采样，并要求用户进行确认.  https://github.com/h3xstream/find-sec-bugs/blob/master/plugin/src/main/resources/metadata/messages.xml",
          ),
            "广播发送" => array(
                "link" => "1.检测传输过程是否是明文传输，文明传输需加密后才能传输.2.检查它是否创建一个新的意图与新的Intent（）.3.检查它是否导入android.content.Intent.  https://github.com/h3xstream/find-sec-bugs/blob/master/plugin/src/main/resources/metadata/messages.xml",
            ),
           "广播接收" => array(
                "link" => "1.检测接收过程是否是明文传输，文明传输需加密后才能传输.2.检查是否导入android.content.BroadcastReceiver.3.始终对任何传入的不受信任的广播内容进行上下文输入验证.  https://github.com/h3xstream/find-sec-bugs/blob/master/plugin/src/main/resources/metadata/messages.xml",
           ),
          "外部文件系统访问" => array(
                "link" => "检查是否导入android.content.Context.  https://github.com/h3xstream/find-sec-bugs/blob/master/plugin/src/main/resources/metadata/messages.xml",
          ),
          "外部文件系统访问" => array(
                "link" => "检查它是否导入android.os.Environment.  https://github.com/h3xstream/find-sec-bugs/blob/master/plugin/src/main/resources/metadata/messages.xml",
          ),
          "Web视图中启用JavaScript" => array(
                "link" => " 1.检查是否导入android.webkit.WebSettings.2.检查是否导入android.webkit.WebView.3.检查是否导入android.webkit.WebChromeClient.  https://github.com/h3xstream/find-sec-bugs/blob/master/plugin/src/main/resources/metadata/messages.xml",
          ),
          "Web视图的JavaScript接口" => array(
                "link" => "1.检查是否导入android.webkit.WebSettings.2.检查是否导入android.webkit.WebView.3.检查是否导入android.webkit.WebChromeClient.  https://github.com/h3xstream/find-sec-bugs/blob/master/plugin/src/main/resources/metadata/messages.xml",
          ),


          "外部存储启用" => array(
                "link" => "使用openFileOutput（）方法在应用程序数据目录中创建\“myfile\”，权限设置为MODEPRIVATE，以便其他应用程序无法访问该文件.此外,先将数据存储在诸如SD卡的外部存储器上.  http://bit.ly/DRD00-J",
          ),
          "web视图的使用" => array(
                "link" => "不要让web视图通过文件方式访问敏感本地资源,任何通过信任边界以外的意图收到的URI都应在使用WebView呈现之前进行验证。例如，检查接收到的URI是不是一个文件：scheme URI，除非应用程序真正使用它。  http://bit.ly/DRD02-J",
          ),
          "应用程序日志记录启用" => array(
                "link" => "声明并使用自定义日志类，以便根据Debug / Release自动打开/关闭日志输出.开发人员可以使用ProGuard删除特定的方法调用.。  http://bit.ly/DRD04-J",
          ),
            "一些标题占位符" => array(
                "link" => "在使用之前和File对象创建后，始终使用Uri.decode（）解码接收到的路径，通过调用File.getCanonicalPath（）来对路径进行规范化，并检查它是否包含在预期目录中。  http://bit.ly/DRD08-J",
            ),
          "数据可读权限" => array(
                "link" => "始终使用MODE PRIVATE权限创建敏感文件，以使其不能被具有相同应用程序的访问权限访问.userid作为创建该文件的应用程序.  http://bit.ly/DRD11-J",
          ),
          "JavaScript接口，使用web视图" => array(
                "link" => "如果不能简单地避免调用addJavascriptInterface（），请指定应用程序仅适用于API JELLY BEAN MR1及以上版本的应用程序清单文件的注释，可以从JavaScript访问JavascriptInterface.  http://bit.ly/DRD13-J",
          ),
          "潜在的隐私风险" => array(
                "link" => "检查是否启用了用户的地理位置，代码将显示一个用户界面以询问用户的权限。如果设置被禁用，它将不会传输地理位置数据。  http://bit.ly/DRD15-J",
          ),
          "不安全的密码" => array(
                "link" => "Android是默认AES的加密方式，默认加密方式是不安全的，因为它使用ECB块加密模式AES加密.  http://bit.ly/DRD17-J",
          ),
          "不安全的密码" => array(
                "link" => "电子密码本模式（ECB）. 在ECB模式下使用不安全的AES分组密码加密算法.  http://bit.ly/DRD18-J",
          ),
          "不安全的通信" => array(
                "link" => " 1.始终验证X.509证书的主题（CN）和URL匹配。 2.始终验证证书是否由受信任的CA签名。 3.始终验证签名是否正确。 4.始终验证证书是否过期。  http://bit.ly/DRD19-J",
          ),
          "NDK API的使用不安全" => array(
            "link" => "1.通过使用umask（）C库调用更改进程的umask.2.始终强制新创建文件的权限与SDK的权限相匹配.3.或者可以使用open（）系统调用显式指定新创建的文件权限.  http://bit.ly/DRD20-J",
          )
    );
