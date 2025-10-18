<?php
// /public_html/ai-chat/index.php
// A clean, brand-friendly chat page you can link at: /ai-chat/
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sombokchab — AI Chat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{
      --bg:#0e1117;
      --panel:#131722;
      --panel-soft:#151a27;
      --fg:#f5f7fa;
      --muted:#99a2b8;
      --border:#1f2430;
      --accent:#3ac0a0;
      --accent-strong:#4ae3bc;
      --radius:18px;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      min-height:100vh;
      background:var(--bg);
      color:var(--fg);
      font:16px/1.5 "Inter", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      display:flex;
      flex-direction:column;
    }
    header{
      padding:32px 20px 12px;
      display:flex;
      justify-content:center;
    }
    .brand{
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:12px;
      text-align:center;
    }
    .brand img{
      width:160px;
      max-width:70vw;
      height:auto;
    }
    .brand h1{
      margin:0;
      font-size:18px;
      letter-spacing:0.08em;
      text-transform:uppercase;
      color:var(--muted);
    }
    .shell{
      flex:1;
      display:flex;
      justify-content:center;
      padding:12px 20px 32px;
    }
    .wrap{
      width:100%;
      max-width:840px;
      background:var(--panel);
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding:32px 32px 24px;
      display:flex;
      flex-direction:column;
      gap:24px;
      box-shadow:0 24px 60px rgba(0,0,0,0.35);
    }
    .hero{
      display:flex;
      flex-direction:column;
      gap:6px;
    }
    .hero h2{
      margin:0;
      font-size:26px;
      font-weight:600;
    }
    .hero p{
      margin:0;
      color:var(--muted);
    }
    .chat{
      display:flex;
      flex-direction:column;
      gap:18px;
      padding-right:6px;
      max-height:60vh;
      overflow-y:auto;
    }
    .bubble{
      padding:14px 18px;
      border-radius:16px;
      max-width:82%;
      background:var(--panel-soft);
      border:1px solid rgba(255,255,255,0.05);
      box-shadow:0 10px 28px rgba(0,0,0,0.28);
      opacity:0;
      transform:translateY(8px);
      transition:opacity 0.25s ease, transform 0.25s ease;
    }
    .bubble.show{opacity:1;transform:translateY(0)}
    .bubble.user{
      align-self:flex-end;
      background:linear-gradient(135deg,rgba(58,192,160,0.25),rgba(58,192,160,0.1));
      border-color:rgba(74,227,188,0.4);
    }
    .bubble.bot{
      align-self:flex-start;
      background:rgba(21,26,39,0.9);
    }
    .meta{
      font-size:12px;
      color:var(--muted);
      margin-bottom:-6px;
      display:flex;
      gap:6px;
    }
    .meta::before{
      content:"";
      width:6px;
      height:6px;
      border-radius:50%;
      background:rgba(255,255,255,0.25);
      align-self:center;
    }
    .meta-user{
      align-self:flex-end;
      flex-direction:row-reverse;
      text-align:right;
    }
    .meta-user::before{background:var(--accent)}
    .meta-bot{align-self:flex-start}
    form{
      margin-top:auto;
      display:flex;
      gap:12px;
      position:sticky;
      bottom:0;
      padding-top:8px;
      background:linear-gradient(180deg,rgba(19,23,34,0),rgba(19,23,34,0.92) 70%);
    }
    input,button,textarea{font:inherit;color:inherit}
    .input-wrap{
      flex:1;
      position:relative;
      display:flex;
      align-items:center;
      background:rgba(10,13,19,0.65);
      border:1px solid var(--border);
      border-radius:calc(var(--radius) - 4px);
      padding:8px 14px;
    }
    textarea{
      flex:1;
      border:0;
      background:transparent;
      resize:vertical;
      min-height:48px;
      max-height:160px;
      padding:0;
      color:var(--fg);
    }
    textarea:focus{outline:none}
    button{
      width:44px;
      height:44px;
      border-radius:50%;
      border:0;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      background:linear-gradient(135deg,var(--accent),var(--accent-strong));
      color:#041015;
      cursor:pointer;
      box-shadow:0 8px 20px rgba(58,192,160,0.35);
      transition:transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }
    button svg{width:20px;height:20px;fill:currentColor}
    button:hover{transform:translateY(-1px);box-shadow:0 12px 24px rgba(58,192,160,0.45)}
    button:active{transform:translateY(0)}
    @media (max-width:768px){
      header{padding:24px 16px 8px}
      .wrap{padding:24px 18px 18px}
      .chat{max-height:none}
      form{position:static;padding-top:0}
    }
  </style>
</head>
<body>
<header>
  <div class="brand">
    <img src="https://sombokchab.com/assets/uploads/media-uploader/sombok-jab-final-logo-061737325403.png" alt="Sombokchab logo" loading="lazy"/>
    <h1>Sombokchab — AI Chat</h1>
  </div>
</header>

<div class="shell">
<div class="wrap">
  <section class="hero">
    <div>
      <h2>Ask anything</h2>
      <p>General questions, buying help, delivery info, simple translations — powered by Shimanto.</p>
    </div>
  </section>

  <div id="log" class="chat" aria-live="polite"></div>

  <form id="f">
    <div class="input-wrap">
      <textarea id="q" placeholder="Ask anything!" required></textarea>
    </div>
    <button type="submit" class="send-btn" aria-label="Send message">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M4.26 3.3c-.95-.4-1.88.5-1.58 1.45l2.12 6.77c.07.21.22.38.42.47l5.19 2.48c.3.14.3.57 0 .71l-5.19 2.48c-.2.1-.35.27-.42.47l-2.12 6.77c-.3.95.63 1.85 1.58 1.45l16.88-7.2c1.02-.44 1.02-1.87 0-2.31L4.26 3.3Z"/>
      </svg>
    </button>
  </form>
</div>
</div>

<script>
const log = document.getElementById('log');
const form = document.getElementById('f');
const q = document.getElementById('q');

function typeText(el, text){
  let index = 0;
  const speed = 16;
  function step(){
    if(index < text.length){
      el.textContent += text[index];
      index++;
      const delay = text[index - 1] === '.' ? 90 : text[index - 1] === ',' ? 70 : speed;
      setTimeout(step, delay);
    }
  }
  step();
}

function add(role, text, {loading=false} = {}){
  const meta = document.createElement('div');
  meta.className = 'meta ' + (role === 'user' ? 'meta-user' : 'meta-bot');
  meta.textContent = role === 'user' ? 'You' : 'Sombokchab AI';
  const div = document.createElement('div'); div.className = 'bubble ' + (role==='user'?'user':'bot');
  if(role === 'assistant' && !loading){
    div.textContent = '';
    requestAnimationFrame(()=>typeText(div, text));
  }else{
    div.textContent = text;
  }
  log.append(meta, div);
  requestAnimationFrame(()=>div.classList.add('show'));
  div.scrollIntoView({behavior:'smooth',block:'end'});
  return div;
}

form.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const prompt = q.value.trim();
  if(!prompt) return;
  q.value = '';
  q.focus();
  add('user', prompt);
  const loadingBubble = add('assistant', 'Thinking…', {loading:true});

  try{
    const r = await fetch('api.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ prompt })
    });
    if(!r.ok) throw new Error('HTTP '+r.status);
    const data = await r.json();
    loadingBubble.textContent = '';
    loadingBubble.classList.remove('user');
    typeText(loadingBubble, (data.reply || '(No response)'));
  }catch(err){
    loadingBubble.textContent = 'Error: ' + err.message;
  }
});

q.addEventListener('keydown', (e)=>{
  if(e.key === 'Enter' && !e.shiftKey){
    e.preventDefault();
    if(typeof form.requestSubmit === 'function'){
      form.requestSubmit();
    }else{
      form.dispatchEvent(new Event('submit', {cancelable:true}));
    }
  }
});
</script>
</body>
</html>
