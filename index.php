<?php
// /public_html/ai-chat/index.php
// A clean, brand-friendly chat page you can link at: /ai-chat/
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sombokchab — AI Chat (Gemini)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{
      --bg:#ffffff;
      --panel:#f7f7f8;
      --fg:#0d0d0f;
      --muted:#6b7280;
      --brand:#c9a227; /* gold-ish accent — adjust to match theme */
      --chip:#111827;
    }
    *{box-sizing:border-box}
    body{margin:0;background:var(--bg);color:var(--fg);font:16px/1.55 system-ui,Segoe UI,Roboto,Helvetica,Arial}
    header{padding:14px 18px;border-bottom:1px solid #e9e9ef;background:var(--panel);position:sticky;top:0}
    .wrap{max-width:900px;margin:0 auto;padding:18px}
    h1{margin:0;font-size:18px}
    .brand{display:flex;align-items:center;gap:10px}
    .brand .dot{width:10px;height:10px;border-radius:50%;background:var(--brand)}
    .chat{display:flex;flex-direction:column;gap:12px;margin-top:14px}
    .bubble{padding:12px 14px;border-radius:14px;max-width:85%;box-shadow:0 2px 10px rgba(0,0,0,.05)}
    .user{align-self:flex-end;background:#eef2ff}
    .bot{align-self:flex-start;background:#f9fafb}
    .meta{font-size:12px;color:var(--muted);margin:6px 2px}
    form{display:flex;gap:10px;position:sticky;bottom:0;padding:12px 0;background:linear-gradient(180deg,rgba(255,255,255,0),var(--bg) 40%)}
    input,button,textarea{font:inherit}
    textarea{flex:1;min-height:52px;max-height:160px;padding:12px 14px;border:1px solid #e6e6ef;background:#fff;color:var(--fg);border-radius:12px;resize:vertical}
    button{padding:12px 16px;border:0;border-radius:12px;background:var(--brand);color:#1a1a1a;font-weight:700;cursor:pointer}
    .tip{color:var(--muted);font-size:12px;margin-top:10px}
    .badge{display:inline-block;padding:4px 8px;border-radius:999px;background:var(--chip);color:#f7f7f8;font-size:12px}
    .hero{display:flex;justify-content:space-between;align-items:center;gap:20px;padding:12px 0 6px}
    .hero h2{margin:0 0 6px 0;font-size:22px}
    .hero p{margin:0;color:var(--muted)}
  </style>
</head>
<body>
<header>
  <div class="wrap brand">
    <span class="dot"></span>
    <h1>Sombokchab — AI Chat (Gemini)</h1>
    <span class="badge">Beta</span>
  </div>
</header>

<div class="wrap">
  <section class="hero">
    <div>
      <h2>Ask anything</h2>
      <p>General questions, buying help, delivery info, simple translations — powered by Google Gemini.</p>
    </div>
  </section>

  <div id="log" class="chat" aria-live="polite"></div>

  <form id="f">
    <textarea id="q" placeholder="Type your question… (e.g., “What is cash on delivery?”)" required></textarea>
    <button type="submit">Send</button>
  </form>

  <p class="tip">Private: your question is sent securely to our server, then to Google Gemini. No keys in the browser.</p>
</div>

<script>
const log = document.getElementById('log');
const form = document.getElementById('f');
const q = document.getElementById('q');

function add(role, text){
  const meta = document.createElement('div'); meta.className='meta'; meta.textContent = role === 'user' ? 'You' : 'Sombokchab AI';
  const div = document.createElement('div'); div.className = 'bubble ' + (role==='user'?'user':'bot');
  div.textContent = text;
  log.append(meta, div);
  div.scrollIntoView({behavior:'smooth',block:'end'});
}

form.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const prompt = q.value.trim();
  if(!prompt) return;
  q.value = '';
  add('user', prompt);
  add('assistant', 'Thinking…');

  const bubbles = [...document.querySelectorAll('.bubble')];
  const loading = bubbles[bubbles.length-1];

  try{
    const r = await fetch('api.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ prompt })
    });
    if(!r.ok) throw new Error('HTTP '+r.status);
    const data = await r.json();
    loading.textContent = data.reply || '(No response)';
  }catch(err){
    loading.textContent = 'Error: ' + err.message;
  }
});
</script>
</body>
</html>
