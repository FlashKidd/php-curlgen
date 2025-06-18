<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>⚡ PHP cURL Code Generator</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Roboto+Mono&display=swap" rel="stylesheet">
    <style>
        /* Reset & Base */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #333;
        }

        header {
            text-align: center;
            padding: 40px 20px 10px;
        }
        header h1 {
            font-size: 2.8rem;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }

        main {
            flex: 1;
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            padding: 0 20px;
        }

        form, #outputContainer {
            background: rgba(255,255,255,0.95);
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            transition: transform .2s;
        }
        form:hover, #outputContainer:hover {
            transform: translateY(-4px);
        }

        label {
            display: block;
            margin-bottom: 16px;
            font-size: 1rem;
            color: #555;
        }
        input[type=text], select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Roboto Mono', monospace;
            margin-top: 6px;
        }

        input[type=button], button {
            display: inline-block;
            background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 100%);
            border: none;
            color: #fff;
            padding: 14px 28px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            letter-spacing: .5px;
            cursor: pointer;
            transition: box-shadow .2s, transform .2s;
            margin-top: 12px;
        }
        input[type=button]:hover, button:hover {
            box-shadow: 0 12px 24px rgba(0,0,0,0.2);
            transform: translateY(-2px) scale(1.02);
        }

        #codeOutput {
            width: 100%;
            height: 320px;
            font-family: 'Roboto Mono', monospace;
            font-size: .95rem;
            margin-top: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            background: #fafafa;
            overflow: auto;
            white-space: pre;
        }

        footer {
            text-align: center;
            padding: 12px;
            font-size: .9rem;
            color: #eee;
        }
    </style>
</head>
<body>

    <header>
        <h1>⚡ PHP cURL Code Generator</h1>
    </header>

    <main>
        <form id="genForm">
            <label>
                URL
                <input type="text" id="url" placeholder="https://api.example.com/endpoint" required>
            </label>

            <label>
                Method
                <select id="method">
                    <option>GET</option>
                    <option>POST</option>
                </select>
            </label>

            <div id="postContainer" style="display: none;">
                <label>
                    Body (JSON or raw)
                    <textarea id="body" rows="4" placeholder='{"key":"value"}'></textarea>
                </label>
            </div>

            <label><input type="checkbox" id="useHeaders"> Include Headers</label>
            <div id="hdrSection" style="display: none; margin-left: 20px;">
                <input type="button" id="addHdr" value="➕ Add Header">
                <div id="hdrFields" style="margin-top: 8px;"></div>
            </div>

            <label><input type="checkbox" id="useCookie"> Use Cookie Jar</label>
            <label><input type="checkbox" id="useExtract"> Extract Substring</label>
            <div id="extractSection" style="display: none; margin-left: 20px;">
                <label>
                    Left Delimiter
                    <input type="text" id="leftDelim" placeholder="start">
                </label>
                <label>
                    Right Delimiter
                    <input type="text" id="rightDelim" placeholder="end">
                </label>
            </div>

            <label><input type="checkbox" id="useJSON"> Parse JSON Key</label>
            <div id="jsonSection" style="display: none; margin-left: 20px;">
                <label>
                    JSON Path (dot notation)
                    <input type="text" id="jsonKey" placeholder="data.user.id">
                </label>
            </div>

            <label><input type="checkbox" id="useRedirect"> Capture Redirect URL</label>
            <label><input type="checkbox" id="useCaptHeaders"> Capture Response Headers</label>
            <div id="capHdrSection" style="display: none; margin-left: 20px;">
                <label>
                    Header Names (comma-separated)
                    <input type="text" id="hdrNames" placeholder="Content-Type,Set-Cookie">
                </label>
            </div>

            <button type="submit">Generate Code</button>
        </form>

        <div id="outputContainer" style="display: none;">
            <h2>Generated PHP Code</h2>
            <textarea id="codeOutput" readonly></textarea>
            <button id="downloadBtn">📄 Download File</button>
        </div>
    </main>

    <footer>
        Built with ❤️ by <strong>FlashKidd</strong> & ChatGPT
    </footer>

    <script>
        // Form controls
        const methodEl       = document.getElementById('method');
        const postCtl        = document.getElementById('postContainer');
        const useHdr         = document.getElementById('useHeaders');
        const hdrSec         = document.getElementById('hdrSection');
        const addHdrBtn      = document.getElementById('addHdr');
        const hdrFields      = document.getElementById('hdrFields');
        const useCookie      = document.getElementById('useCookie');
        const useExtract     = document.getElementById('useExtract');
        const extractSec     = document.getElementById('extractSection');
        const useJSON        = document.getElementById('useJSON');
        const jsonSec        = document.getElementById('jsonSection');
        const useRedirect    = document.getElementById('useRedirect');
        const useCaptHeaders = document.getElementById('useCaptHeaders');
        const capHdrSec      = document.getElementById('capHdrSection');
        const form           = document.getElementById('genForm');
        const outDiv         = document.getElementById('outputContainer');
        const codeTA         = document.getElementById('codeOutput');
        const dlBtn          = document.getElementById('downloadBtn');

        // Toggle sections
        methodEl.addEventListener('change', () => {
            postCtl.style.display = methodEl.value === 'POST' ? 'block' : 'none';
        });
        useHdr.addEventListener('change', () => {
            hdrSec.style.display = useHdr.checked ? 'block' : 'none';
        });
        addHdrBtn.addEventListener('click', () => {
            const div = document.createElement('div');
            div.innerHTML = '<input type="text" placeholder="Header: Value" style="width:100%; margin:6px 0;">';
            hdrFields.appendChild(div);
        });
        useExtract.addEventListener('change', () => {
            extractSec.style.display = useExtract.checked ? 'block' : 'none';
        });
        useJSON.addEventListener('change', () => {
            jsonSec.style.display = useJSON.checked ? 'block' : 'none';
        });
        useCaptHeaders.addEventListener('change', () => {
            capHdrSec.style.display = useCaptHeaders.checked ? 'block' : 'none';
        });

        // Initial visibility
        postCtl.style.display = methodEl.value === 'POST' ? 'block' : 'none';
        hdrSec.style.display = useHdr.checked ? 'block' : 'none';
        extractSec.style.display = useExtract.checked ? 'block' : 'none';
        jsonSec.style.display = useJSON.checked ? 'block' : 'none';
        capHdrSec.style.display = useCaptHeaders.checked ? 'block' : 'none';

        // Generate code
        form.addEventListener('submit', e => {
            e.preventDefault();
            let code = "<?php\n";
            code += `$ch = curl_init();\n`;
            code += `curl_setopt($ch, CURLOPT_URL, '${document.getElementById('url').value}');\n`;
            code += `curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);\n`;
            code += `curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);\n`;

            // Cookie jar
            if (useCookie.checked) {
                code += "curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookiejar.txt');\n";
                code += "curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookiejar.txt');\n";
            }

            // Headers
            if (useHdr.checked) {
                const hdrs = Array.from(hdrFields.querySelectorAll('input')).map(i => i.value);
                code += "curl_setopt($ch, CURLOPT_HTTPHEADER, [\n";
                hdrs.forEach(h => code += `    '${h}',\n`);
                code += "]);\n";
            }

            // POST
            if (methodEl.value === 'POST') {
                code += "curl_setopt($ch, CURLOPT_POST, true);\n";
                const b = document.getElementById('body').value;
                if (b.trim().startsWith('{')) {
                    code += `curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(json_decode('${b}', true)));\n`;
                } else {
                    code += `curl_setopt($ch, CURLOPT_POSTFIELDS, '${b}');\n`;
                }
            }

            // Capture headers or redirect
            if (useCaptHeaders.checked || useRedirect.checked) {
                code += "curl_setopt($ch, CURLOPT_HEADER, true);\n";
            }

            code += "\n$response = curl_exec($ch);\n";

            // Parse headers
            if (useCaptHeaders.checked) {
                code += "$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);\n";
                code += "$header_str  = substr($response, 0, $header_size);\n";
                code += "$body        = substr($response, $header_size);\n";
                code += "$lines       = explode("\r\n", $header_str);\n";
                code += "$headers_arr = [];\n";
                code += `foreach ($lines as $ln) {\n`;
                code += `    if (strpos($ln, ':') !== false) {\n`;
                code += `        list($k, $v) = explode(': ', $ln, 2);\n`;
                code += `        $headers_arr[$k] = $v;\n`;
                code += `    }\n`;
                code += `}\n`;
                const names = document.getElementById('hdrNames').value.split(',').map(s => s.trim());
                names.forEach(n => {
                    code += `$${n.replace(/[^A-Za-z0-9_]/g, '_')} = \$headers_arr['${n}'] ?? null;\n`;
                });
            } else {
                code += "$body = $response;\n";
            }

            // Redirect URL
            if (useRedirect.checked) {
                code += "$redirectedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);\n";
            }

            // Substring extract
            if (useExtract.checked) {
                const l = document.getElementById('leftDelim').value;
                const r = document.getElementById('rightDelim').value;
                code += `if (false !== ($st = strpos($body, '${l}'))) { $st += strlen('${l}'); $body = substr($body, $st); }\n`;
                code += `if (false !== ($ed = strpos($body, '${r}'))) { $body = substr($body, 0, $ed); }\n`;
            }

            // JSON parse
            if (useJSON.checked) {
                const path = document.getElementById('jsonKey').value;
                code += "$dataArr = json_decode($body, true);\n";
                code += "if (json_last_error() === JSON_ERROR_NONE) {\n";
                code += "    $val = $dataArr;\n";
                code += `    foreach (explode('.', '${path}') as $k) { $val = \$val[\$k] ?? null; }\n`;
                code += "    var_dump($val);\n";
                code += "}\n";
            }

            code += "\nif (curl_errno($ch)) { echo 'Error: ' . curl_error($ch); } else { echo $body; }\n";
            code += "curl_close($ch);\n?>";

            codeTA.textContent = code;
            outDiv.style.display = 'block';
        });

        // Download snippet
        dlBtn.addEventListener('click', () => {
            const blob = new Blob([codeTA.textContent], { type: 'text/php' });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href     = url;
            a.download = 'curl_request.php';
            a.click();
            URL.revokeObjectURL(url);
        });
    </script>
</body>
</html>
