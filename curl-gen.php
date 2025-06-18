<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>⚡ PHP cURL Code Generator</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto+Mono&display=swap" rel="stylesheet">
    <style>
        /* Reset & Base */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; width: 100%; }
        body {
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(135deg, #1f1c2c 0%, #928dab 100%);
            color: #eee;
            display: flex;
            flex-direction: column;
        }
        header { text-align: center; padding: 40px 20px; }
        header h1 {
            font-size: 3.2rem;
            color: #00ffff;
            text-shadow: 0 0 10px rgba(0,255,255,0.7);
        }
        main {
            flex: 1;
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }
        form, #outputContainer {
            background: rgba(20,20,30,0.95);
            border: 2px solid #00ffff;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 30px;
            box-shadow: 0 0 20px rgba(0,255,255,0.5);
            width: 100%;
        }
        fieldset {
            border: 2px solid #ff00ff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        fieldset legend {
            font-family: 'Orbitron', sans-serif;
            color: #ff00ff;
            font-size: 1.4rem;
            padding: 0 10px;
        }
        label {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            font-size: 1.1rem;
            color: #ddd;
        }
        label span {
            flex: 1;
            padding-left: 10px;
        }
        label input[type=checkbox] {
            transform: scale(1.3);
            accent-color: #00ffff;
        }
        input[type=text], select, textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #555;
            border-radius: 10px;
            font-family: 'Roboto Mono', monospace;
            font-size: 1.1rem;
            margin-top: 6px;
            background: rgba(30,30,40,0.8);
            color: #eee;
            transition: border-color .2s;
        }
        input[type=text]:focus, select:focus, textarea:focus {
            border-color: #00ffff;
            outline: none;
        }
        .hdr-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 6px 0;
        }
        .hdr-item input {
            flex: 1 1 auto;
            width: auto !important;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #555;
            font-size: 1rem;
            background: rgba(30,30,40,0.8);
            color: #eee;
            min-width: 0;
        }
        .removeHdr {
            background: none;
            border: none;
            color: #ff00ff;
            font-size: 1.4rem;
            cursor: pointer;
            flex: 0 0 auto;
            padding: 4px;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
        }
        input[type=button], button {
            display: inline-block;
            background: linear-gradient(45deg, #ff00ff, #00ffff);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 14px 0;
            width: 100%;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 12px;
            box-shadow: 0 0 10px rgba(255,0,255,0.7);
            transition: transform .2s, box-shadow .2s;
        }
        input[type=button]:hover, button:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 0 20px rgba(255,0,255,0.9);
        }
        #codeOutput {
            width: 100%;
            height: 400px;
            font-family: 'Roboto Mono', monospace;
            font-size: 1.05rem;
            line-height: 1.5;
            padding: 14px;
            border: 2px solid #555;
            border-radius: 10px;
            background: rgba(20,20,30,0.9);
            color: #0f0;
            overflow: auto;
            white-space: pre;
            margin-top: 16px;
        }
        h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.6rem;
            color: #00ffff;
            margin-bottom: 12px;
            text-shadow: 0 0 8px rgba(0,255,255,0.5);
        }
        @media (max-width: 600px) {
            fieldset { padding: 10px; }
            header h1 { font-size: 2.4rem; }
            label { font-size: 1rem; }
            input[type=button], button { font-size: 1rem; }
        }
    </style>
</head>
<body>
<header>
    <h1>⚡ PHP cURL Code Generator</h1>
</header>
<main>
    <form id="genForm">
        <label><span>URL</span>
            <input type="text" id="url" placeholder="https://api.example.com/endpoint" required>
        </label>
        <label><span>Method</span>
            <select id="method">
                <option>GET</option>
                <option>POST</option>
            </select>
        </label>
        <div id="postContainer" style="display:none; margin-top:10px;">
            <label><span>Body (JSON or raw)</span>
                <textarea id="body" rows="4" placeholder='{"key":"value"}'></textarea>
            </label>
        </div>

        <fieldset>
            <legend>Options</legend>
            <label><input type="checkbox" id="useHeaders"><span>Include Headers</span></label>
            <div id="hdrSection" style="display:none; margin-top:10px;">
                <input type="button" id="addHdr" value="➕ Add Header">
                <div id="hdrFields"></div>
            </div>
            <label><input type="checkbox" id="useCookie"><span>Use Cookie Jar</span></label>
            <label><input type="checkbox" id="useExtract"><span>Extract Substring</span></label>
            <div id="extractSection" style="display:none; margin-top:10px;">
                <label><span>Start Delimiter</span><input type="text" id="leftDelim" placeholder="start"></label>
                <label><span>End Delimiter</span><input type="text" id="rightDelim" placeholder="end"></label>
            </div>
            <label><input type="checkbox" id="useJSON"><span>Parse JSON Key</span></label>
            <div id="jsonSection" style="display:none; margin-top:10px;">
                <label><span>JSON Path (dot notation)</span>
                    <input type="text" id="jsonKey" placeholder="data.user.id">
                </label>
            </div>
            <label><input type="checkbox" id="useRedirect"><span>Capture Redirect URL</span></label>
            <label><input type="checkbox" id="useCaptHeaders"><span>Capture Response Headers</span></label>
            <div id="capHdrSection" style="display:none; margin-top:10px;">
                <label><span>Header Names (comma-separated)</span>
                    <input type="text" id="hdrNames" placeholder="Content-Type,Set-Cookie">
                </label>
            </div>
        </fieldset>

        <button type="submit">Generate Code</button>
    </form>
    <div id="outputContainer" style="display:none;">
        <h2>Generated PHP Code</h2>
        <textarea id="codeOutput" readonly></textarea>
        <button id="downloadBtn">📄 Download File</button>
    </div>
</main>
<footer style="text-align:center; padding:16px;">Built with ❤️ by <strong>FlashKidd</strong> & ChatGPT</footer>
<script>
    // Controls
    const methodEl    = document.getElementById('method');
    const postCtl     = document.getElementById('postContainer');
    const useHdr      = document.getElementById('useHeaders');
    const hdrSec      = document.getElementById('hdrSection');
    const addHdrBtn   = document.getElementById('addHdr');
    const hdrFld      = document.getElementById('hdrFields');
    const useCookie   = document.getElementById('useCookie');
    const useExtract  = document.getElementById('useExtract');
    const extractSec  = document.getElementById('extractSection');
    const useJSON     = document.getElementById('useJSON');
    const jsonSec     = document.getElementById('jsonSection');
    const useRedirect = document.getElementById('useRedirect');
    const useCH       = document.getElementById('useCaptHeaders');
    const chSec       = document.getElementById('capHdrSection');
    const form        = document.getElementById('genForm');
    const outDiv      = document.getElementById('outputContainer');
    const codeTA      = document.getElementById('codeOutput');
    const dlBtn       = document.getElementById('downloadBtn');

    // Toggle sections
    methodEl.addEventListener('change', () => postCtl.style.display = methodEl.value === 'POST' ? 'block' : 'none');
    useHdr.addEventListener('change', () => hdrSec.style.display = useHdr.checked ? 'block' : 'none');
    addHdrBtn.addEventListener('click', () => {
        const item = document.createElement('div');
        item.className = 'hdr-item';
        const inp = document.createElement('input');
        inp.type = 'text'; inp.placeholder = 'Header: Value';
        const btn = document.createElement('button');
        btn.type = 'button'; btn.className = 'removeHdr'; btn.textContent = '×';
        btn.addEventListener('click', () => item.remove());
        item.appendChild(inp);
        item.appendChild(btn);
        hdrFld.appendChild(item);
    });
    useExtract.addEventListener('change', () => extractSec.style.display = useExtract.checked ? 'block' : 'none');
    useJSON.addEventListener('change', () => jsonSec.style.display = useJSON.checked ? 'block' : 'none');
    useCH.addEventListener('change', () => chSec.style.display = useCH.checked ? 'block' : 'none');

    // Generate code
    form.addEventListener('submit', e => {
        e.preventDefault();
        let code = "<?php\n";
        code += "$ch = curl_init();\n";
        code += `curl_setopt($ch, CURLOPT_URL, '${document.getElementById('url').value}');\n`;
        code += "curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);\n";
        code += "curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);\n";
        if (useCookie.checked) {
            code += "curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookiejar.txt');\n";
            code += "curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookiejar.txt');\n";
        }
        if (useHdr.checked) {
            const hdrs = Array.from(hdrFld.querySelectorAll('input')).map(i => i.value.trim()).filter(v => v);
            code += "curl_setopt($ch, CURLOPT_HTTPHEADER, [\n";
            hdrs.forEach(h => code += `    '${h}',\n`);
            code += "]);\n";
        }
        if (methodEl.value === 'POST') {
            code += "curl_setopt($ch, CURLOPT_POST, true);\n";
            const b = document.getElementById('body').value.trim();
            if (b.startsWith('{')) {
                code += `curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(json_decode('${b.replace(/'/g, "\\'")}', true)));\n`;
            } else {
                code += `curl_setopt($ch, CURLOPT_POSTFIELDS, '${b.replace(/'/g, "\\'")}');\n`;
            }
        }
        if (useCH.checked || useRedirect.checked) code += "curl_setopt($ch, CURLOPT_HEADER, true);\n";
        code += "\n$response = curl_exec($ch);\n";
        if (useCH.checked) {
            code += "$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);\n";
            code += "$header_str  = substr($response, 0, $header_size);\n";
            code += "$body        = substr($response, $header_size);\n";
            code += "// parse headers into associative array\n";
            code += "$header_lines = explode(\"\\r\\n\", trim($header_str));\n";
            code += "$headers      = [];\n";
            code += "foreach ($header_lines as $line) {\n";
            code += "    if (strpos($line, ': ') !== false) {\n";
            code += "        list($name, $value) = explode(': ', $line, 2);\n";
            code += "        $headers[$name] = $value;\n";
            code += "    }\n";
            code += "}\n";
            const names = document.getElementById('hdrNames').value.split(',').map(n => n.trim()).filter(n => n);
            names.forEach(n => {
                const v = n.replace(/[^A-Za-z0-9_]/g, '_');
                code += `$${v} = $headers['${n}'] ?? null;\n`;
            });
        } else {
            code += "$body = $response;\n";
        }
        if (useRedirect.checked) code += "$redirectUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);\n";
        if (useExtract.checked) {
            const l = document.getElementById('leftDelim').value.replace(/'/g, "\\'");
            const r = document.getElementById('rightDelim').value.replace(/'/g, "\\'");
            code += `if (false !== ($st = strpos($body, '${l}'))) {\n`;
            code += `    $st += strlen('${l}');\n`;
            code += `    $body = substr($body, $st);\n`;
            code += `}\n`;
            code += `if (false !== ($ed = strpos($body, '${r}'))) {\n`;
            code += `    $body = substr($body, 0, $ed);\n`;
            code += `}\n`;
        }
        if (useJSON.checked) {
            const path = document.getElementById('jsonKey').value.replace(/'/g, "\\'");
            code += "$data = json_decode($body, true);\n";
            code += "if (json_last_error() === JSON_ERROR_NONE) {\n";
            code += "    $val = $data;\n";
            code += "    foreach (explode('.', '" + path + "') as $k) {\n";
            code += "        $val = $val[$k] ?? null;\n";
            code += "    }\n";
            code += "    var_dump($val);\n";
            code += "}\n";
        }
        code += "if (curl_errno($ch)) {\n";
        code += "    echo 'Error: ' . curl_error($ch);\n";
        code += "} else {\n";
        code += "    echo $body;\n";
        code += "}\n";
        code += "curl_close($ch);\n?>";
        codeTA.textContent = code;
        outDiv.style.display = 'block';
    });
    // Download
    dlBtn.addEventListener('click', () => {
        const blob = new Blob([codeTA.textContent], { type: 'text/php' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url;
        a.download = 'curl_request.php'; a.click(); URL.revokeObjectURL(url);
    });
</script>
</body>
</html>
