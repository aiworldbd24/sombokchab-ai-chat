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
      --bg:#020611;
      --bg-glow:rgba(8,27,86,0.4);
      --panel:rgba(13,23,55,0.35);
      --fg:#f8fbff;
      --muted:rgba(197,206,233,0.78);
      --brand:#00f5ff;
      --chip:rgba(4,15,35,0.65);
      --glass-border:rgba(255,255,255,0.15);
      --blur:26px;
      --radius:22px;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      min-height:100vh;
      background:
        radial-gradient(circle at 15% 20%, rgba(0,212,255,0.15) 0%, transparent 45%),
        radial-gradient(circle at 85% 30%, rgba(188,45,255,0.18) 0%, transparent 50%),
        linear-gradient(160deg,#02030a 0%,#030b1d 40%,#020611 100%);
      color:var(--fg);
      font:16px/1.55 "Inter", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      position:relative;
      overflow-x:hidden;
    }
    body::before{
      content:"";
      position:fixed;
      inset:0;
      background:radial-gradient(circle at 50% 0%,rgba(0,255,242,0.15),transparent 45%);
      pointer-events:none;
    }
    header{
      padding:16px 18px;
      border-bottom:1px solid var(--glass-border);
      background:rgba(3,9,24,0.78);
      backdrop-filter:blur(18px);
      position:sticky;
      top:0;
      z-index:10;
    }
    .wrap{
      max-width:920px;
      margin:0 auto;
      padding:24px;
      border:1px solid var(--glass-border);
      background:var(--panel);
      backdrop-filter:blur(var(--blur));
      border-radius:var(--radius);
      box-shadow:0 30px 80px rgba(0,0,0,0.35);
    }
    header .wrap{
      max-width:none;
      padding:0;
      margin:0;
      border:none;
      background:none;
      box-shadow:none;
      backdrop-filter:none;
    }
    .shell{padding:28px 18px 60px;display:flex;justify-content:center}
    h1{margin:0;font-size:18px;letter-spacing:0.08em;text-transform:uppercase}
    .brand{display:flex;align-items:center;gap:12px}
    .brand .dot{width:12px;height:12px;border-radius:50%;background:var(--brand);box-shadow:0 0 12px rgba(0,245,255,0.9)}
    .chat{display:flex;flex-direction:column;gap:16px;margin-top:20px}
    .bubble{
      padding:14px 18px;
      border-radius:18px;
      max-width:82%;
      background:rgba(8,19,52,0.75);
      border:1px solid rgba(255,255,255,0.04);
      box-shadow:0 18px 40px rgba(0,0,0,0.35);
      opacity:0;
      transform:translateY(8px) scale(0.98);
      transition:opacity 0.35s ease, transform 0.35s ease;
    }
    .bubble.show{opacity:1;transform:translateY(0) scale(1)}
    .user{align-self:flex-end;background:linear-gradient(135deg,rgba(0,245,255,0.18),rgba(0,245,255,0.05));border:1px solid rgba(0,245,255,0.35)}
    .bot{align-self:flex-start;background:linear-gradient(135deg,rgba(255,255,255,0.08),rgba(0,0,0,0.25));}
    .meta{font-size:12px;color:var(--muted);margin:4px 6px 0}
    form{display:flex;gap:14px;position:sticky;bottom:0;padding:22px 0;background:linear-gradient(180deg,rgba(2,3,10,0),rgba(2,6,17,0.92) 45%)}
    input,button,textarea{font:inherit;color:inherit}
    .input-wrap{position:relative;flex:1;display:flex;align-items:stretch}
    .input-wrap::before{
      content:"";
      position:absolute;
      inset:-2px;
      border-radius:20px;
      background:linear-gradient(120deg,rgba(0,245,255,0.75),rgba(111,92,255,0.55),rgba(0,245,255,0.75));
      opacity:0.9;
      filter:blur(9px);
      animation:aurora 9s ease-in-out infinite;
      z-index:0;
    }
    textarea{
      position:relative;
      flex:1;
      min-height:58px;
      max-height:180px;
      padding:16px 18px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,0.1);
      background:rgba(4,12,30,0.75);
      backdrop-filter:blur(18px);
      color:var(--fg);
      resize:vertical;
      z-index:1;
      box-shadow:inset 0 0 0 1px rgba(255,255,255,0.04);
    }
    textarea:focus{outline:none;box-shadow:0 0 0 2px rgba(0,245,255,0.6)}
    button{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:14px 24px;
      border:0;
      border-radius:18px;
      background:linear-gradient(135deg,rgba(0,245,255,0.85),rgba(0,157,255,0.65));
      color:#020611;
      font-weight:700;
      cursor:pointer;
      letter-spacing:0.04em;
      box-shadow:0 18px 34px rgba(0,245,255,0.35);
      transition:transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    }
    button svg{width:20px;height:20px;display:block;filter:drop-shadow(0 0 4px rgba(0,0,0,0.35))}
    button span{display:block}
    .send-btn span:first-child{display:flex;align-items:center}
    .send-btn span:last-child{font-size:15px;text-transform:uppercase;letter-spacing:0.08em}
    button:hover{transform:translateY(-2px);box-shadow:0 22px 38px rgba(0,245,255,0.45);background:linear-gradient(135deg,rgba(0,245,255,0.95),rgba(0,157,255,0.75))}
    button:active{transform:translateY(0)}
    .hero{display:flex;justify-content:space-between;align-items:center;gap:20px;padding:12px 0 6px}
    .hero h2{margin:0 0 10px 0;font-size:28px;letter-spacing:0.03em}
    .hero p{margin:0;color:var(--muted);max-width:420px}
    @keyframes aurora{
      0%{transform:rotate(0deg) scale(1);opacity:0.85}
      35%{transform:rotate(140deg) scale(1.05);opacity:1}
      70%{transform:rotate(260deg) scale(1);opacity:0.88}
      100%{transform:rotate(360deg) scale(1.03);opacity:0.95}
    }
    @media (max-width:768px){
      .wrap{padding:20px}
      .hero{flex-direction:column;align-items:flex-start}
      .bubble{max-width:92%}
      form{flex-direction:column}
      .input-wrap{width:100%}
      button{width:100%}
    }
  </style>
</head>
<body>
<header>
  <div class="wrap brand">
    <span class="dot"></span>
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
      <textarea id="q" placeholder="Ask Shimanto for product guidance, delivery updates, or translations." required></textarea>
    </div>
    <button type="submit" class="send-btn">
      <span aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3.2 11.1c-.9-.4-.9-1.8.1-2.1l16.3-6.3c1-.4 2 0 2.4 1s0 2.1-.9 2.5L14 10.8c-.3.1-.3.5 0 .6l7.3 4.2c.9.5 1.2 1.6.7 2.5-.5.9-1.6 1.2-2.5.7l-16.4-9.1Z" fill="#020611"/>
          <path d="M8.9 13.2c-.2-.1-.4.1-.3.3l1.2 5.6c.2.9-.6 1.7-1.5 1.5-.6-.1-1.1-.6-1.2-1.1l-1.8-9.3c-.1-.5.2-1 .7-1.2l9.3-3.8c.9-.4 1.8.5 1.5 1.5l-1.5 4.7c-.1.3-.5.3-.6.1L13 10c-.1-.1-.3-.1-.4 0l-3.7 3.2Z" fill="#00f5ff"/>
        </svg>
      </span>
      <span>Send</span>
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
  const meta = document.createElement('div'); meta.className='meta'; meta.textContent = role === 'user' ? 'You' : 'Sombokchab AI';
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
