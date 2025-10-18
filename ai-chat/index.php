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
      color-scheme: light;
      --transition:0.35s ease;
      --bg:#f6f8fc;
      --page-gradient:radial-gradient(120% 160% at 15% 10%, #ffffff 0%, #eef2f9 55%, #e2e8f5 100%);
      --panel:rgba(255,255,255,0.78);
      --panel-solid:#ffffff;
      --fg:#1e2433;
      --muted:#62708a;
      --border:rgba(30,36,51,0.1);
      --accent:#3ac0a0;
      --accent-strong:#2c9c84;
      --accent-soft:rgba(58,192,160,0.16);
      --shadow:0 28px 60px rgba(31,43,72,0.18);
      --bot-bubble:rgba(255,255,255,0.85);
      --user-bubble:linear-gradient(135deg, rgba(58,192,160,0.18), rgba(58,192,160,0.08));
    }
    :root[data-theme="dark"]{
      color-scheme: dark;
      --bg:#080b12;
      --page-gradient:radial-gradient(120% 140% at 20% 12%, rgba(72,93,132,0.25) 0%, rgba(20,25,38,0.92) 55%, rgba(8,11,18,1) 100%);
      --panel:rgba(17,21,32,0.78);
      --panel-solid:#131927;
      --fg:#f5f7fa;
      --muted:#9aa6c2;
      --border:rgba(255,255,255,0.07);
      --accent:#4ae3bc;
      --accent-strong:#36b696;
      --accent-soft:rgba(74,227,188,0.2);
      --shadow:0 32px 70px rgba(0,0,0,0.48);
      --bot-bubble:rgba(20,26,39,0.92);
      --user-bubble:linear-gradient(135deg, rgba(74,227,188,0.3), rgba(58,192,160,0.18));
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      min-height:100vh;
      display:flex;
      flex-direction:column;
      align-items:center;
      font:16px/1.6 "Inter", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      color:var(--fg);
      background:var(--bg);
      transition:background var(--transition), color var(--transition);
      position:relative;
      overflow-x:hidden;
    }
    body::before{
      content:"";
      position:fixed;
      inset:0;
      background:var(--page-gradient);
      z-index:-2;
      transition:opacity var(--transition);
    }
    body::after{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:radial-gradient(900px 900px at 18% 18%, rgba(58,192,160,0.18), transparent 70%),
                 radial-gradient(720px 720px at 82% 6%, rgba(58,192,160,0.1), transparent 70%);
      opacity:0.45;
      z-index:-1;
      transition:opacity var(--transition), background var(--transition);
    }
    [data-theme="dark"] body::after{
      background:radial-gradient(820px 820px at 20% 18%, rgba(74,227,188,0.16), transparent 68%),
                 radial-gradient(680px 680px at 80% 10%, rgba(58,192,160,0.12), transparent 70%);
      opacity:0.55;
    }
    header{
      width:100%;
      padding:32px 24px 12px;
      display:flex;
      justify-content:center;
    }
    .header-inner{
      width:100%;
      max-width:840px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:18px;
    }
    .brand{
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:12px;
      text-align:center;
      margin:0 auto;
    }
    .brand img{
      width:160px;
      max-width:70vw;
      height:auto;
      filter:drop-shadow(0 18px 30px rgba(23,34,57,0.24));
      transition:filter var(--transition);
    }
    :root[data-theme="dark"] .brand img{filter:drop-shadow(0 18px 40px rgba(0,0,0,0.45))}
    .brand h1{
      margin:0;
      font-size:18px;
      letter-spacing:0.08em;
      text-transform:uppercase;
      color:var(--muted);
    }
    .theme-toggle{
      border:0;
      background:var(--panel-solid);
      color:var(--fg);
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 18px;
      border-radius:999px;
      box-shadow:0 16px 36px rgba(22,32,55,0.12);
      cursor:pointer;
      transition:transform 0.2s ease, box-shadow 0.3s ease, background var(--transition), color var(--transition);
      position:relative;
      backdrop-filter:blur(18px);
    }
    .theme-toggle:hover{transform:translateY(-1px);box-shadow:0 20px 40px rgba(22,32,55,0.18)}
    .theme-toggle:active{transform:translateY(0)}
    .theme-toggle__icon{
      width:18px;
      height:18px;
      border-radius:50%;
      background:linear-gradient(135deg, var(--accent), var(--accent-strong));
      position:relative;
      transition:background var(--transition), transform var(--transition);
      box-shadow:0 0 0 4px rgba(58,192,160,0.12);
    }
    .theme-toggle__icon::after{
      content:"";
      position:absolute;
      inset:-6px;
      border-radius:inherit;
      background:radial-gradient(circle at center, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0) 70%);
      opacity:0.8;
      transition:transform var(--transition), opacity var(--transition);
    }
    .theme-toggle__icon[data-theme="dark"]{
      background:linear-gradient(135deg, #1c2335, #3a4760);
      box-shadow:0 0 0 4px rgba(26,33,53,0.26);
    }
    .theme-toggle__icon[data-theme="dark"]::after{
      background:radial-gradient(circle at 35% 30%, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.4) 45%, rgba(255,255,255,0) 70%);
      transform:translateX(3px) scale(0.82);
      opacity:0.9;
    }
    .theme-toggle__text{font-weight:600;font-size:14px;letter-spacing:0.02em}
    .shell{
      flex:1;
      display:flex;
      justify-content:center;
      padding:12px 24px 40px;
      width:100%;
    }
    .wrap{
      width:100%;
      max-width:840px;
      background:var(--panel);
      border:1px solid var(--border);
      border-radius:24px;
      padding:32px clamp(20px, 4vw, 36px) 26px;
      display:flex;
      flex-direction:column;
      gap:24px;
      box-shadow:var(--shadow);
      backdrop-filter:blur(24px);
      transition:background var(--transition), border-color var(--transition), box-shadow var(--transition);
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
      gap:20px;
      padding-right:8px;
      max-height:60vh;
      overflow-y:auto;
      scrollbar-width:none;
    }
    .chat::-webkit-scrollbar{width:0;height:0}
    .meta{
      font-size:12px;
      color:var(--muted);
      display:flex;
      gap:6px;
      margin-bottom:-6px;
      letter-spacing:0.04em;
    }
    .meta::before{
      content:"";
      width:6px;
      height:6px;
      border-radius:50%;
      background:rgba(58,192,160,0.45);
      align-self:center;
    }
    .meta-user{
      align-self:flex-end;
      flex-direction:row-reverse;
      text-align:right;
    }
    .meta-user::before{background:var(--accent)}
    .meta-bot{align-self:flex-start}
    .bubble{
      padding:16px 20px;
      border-radius:18px;
      max-width:82%;
      background:var(--bot-bubble);
      border:1px solid rgba(255,255,255,0.08);
      box-shadow:0 18px 38px rgba(18,24,38,0.16);
      opacity:0;
      transform:translateY(12px);
      transition:opacity 0.28s ease, transform 0.28s ease, background var(--transition), border-color var(--transition);
      line-height:1.6;
      letter-spacing:0.01em;
    }
    .bubble.show{opacity:1;transform:translateY(0)}
    .bubble.user{
      align-self:flex-end;
      background:var(--user-bubble);
      border:1px solid rgba(74,227,188,0.35);
      color:var(--fg);
      box-shadow:0 16px 32px rgba(34,51,54,0.18);
    }
    .bubble.bot{
      align-self:flex-start;
      border-color:rgba(255,255,255,0.05);
    }
    .bubble.bot.bot-formatted{
      display:flex;
      flex-direction:column;
      gap:12px;
    }
    .bubble.bot.bot-formatted h3{
      margin:0;
      font-size:18px;
      font-weight:600;
      letter-spacing:0.01em;
      color:var(--accent-strong);
    }
    .bubble.bot.bot-formatted p{
      margin:0;
    }
    form{
      margin-top:auto;
      display:flex;
      gap:12px;
      position:sticky;
      bottom:0;
      padding-top:12px;
      background:linear-gradient(180deg, rgba(255,255,255,0), var(--panel) 60%);
      align-items:stretch;
      transition:background var(--transition);
    }
    :root[data-theme="dark"] form{background:linear-gradient(180deg, rgba(19,23,34,0), var(--panel) 60%)}
    .input-wrap{
      flex:1;
      position:relative;
      border-radius:18px;
      background:var(--panel-solid);
      border:1px solid rgba(58,192,160,0.28);
      padding:14px 18px;
      box-shadow:0 20px 40px rgba(23,35,56,0.16);
      transition:box-shadow var(--transition), transform var(--transition), background var(--transition), border-color var(--transition);
      overflow:hidden;
    }
    .input-wrap::before{
      content:"";
      position:absolute;
      inset:-18px;
      border-radius:inherit;
      background:radial-gradient(circle at 30% 30%, rgba(74,227,188,0.35), transparent 70%),
                 radial-gradient(circle at 75% 70%, rgba(58,192,160,0.3), transparent 65%);
      opacity:0.6;
      filter:blur(18px);
      animation:inputGlow 6s ease-in-out infinite;
      pointer-events:none;
      transition:opacity var(--transition);
    }
    .input-wrap:focus-within{
      transform:translateY(-1px);
      box-shadow:0 26px 50px rgba(26,40,63,0.22), 0 0 0 2px rgba(74,227,188,0.25);
      border-color:rgba(74,227,188,0.55);
    }
    .input-wrap:focus-within::before{opacity:0.85}
    textarea{
      position:relative;
      z-index:1;
      flex:1;
      border:0;
      background:transparent;
      resize:vertical;
      min-height:48px;
      max-height:160px;
      padding:0;
      color:var(--fg);
      line-height:1.5;
      width:100%;
    }
    textarea:focus{outline:none}
    .send-btn{
      border:0;
      display:flex;
      align-items:center;
      justify-content:center;
      min-width:56px;
      padding:0 20px;
      border-radius:18px;
      background:linear-gradient(135deg, var(--accent), var(--accent-strong));
      color:#041015;
      cursor:pointer;
      box-shadow:0 18px 32px rgba(58,192,160,0.32);
      transition:transform 0.2s ease, box-shadow 0.2s ease, background var(--transition), color var(--transition);
    }
    .send-btn:hover{transform:translateY(-1px);box-shadow:0 22px 38px rgba(58,192,160,0.42)}
    .send-btn:active{transform:translateY(0)}
    .send-btn svg{width:18px;height:18px;fill:currentColor}
    @keyframes inputGlow{
      0%,100%{opacity:0.45;transform:scale(0.98)}
      50%{opacity:0.85;transform:scale(1.02)}
    }
    @media (prefers-reduced-motion: reduce){
      *, *::before, *::after{animation-duration:0.01ms !important; animation-iteration-count:1 !important; transition-duration:0.01ms !important; scroll-behavior:auto !important;}
    }
    @media (max-width:768px){
      header{padding:24px 18px 8px}
      .header-inner{flex-direction:column;align-items:center;gap:16px}
      .theme-toggle{order:-1}
      .wrap{padding:26px 18px 20px}
      .chat{max-height:none}
      form{position:static;padding-top:8px}
      .input-wrap{min-height:72px}
    }
  </style>
</head>
<body>
<header>
  <div class="header-inner">
    <div class="brand">
      <img src="https://sombokchab.com/assets/uploads/media-uploader/sombok-jab-final-logo-061737325403.png" alt="Sombokchab logo" loading="lazy"/>
      <h1>Sombokchab — AI Chat</h1>
    </div>
    <button type="button" id="themeToggle" class="theme-toggle" aria-label="Toggle color theme" aria-pressed="false">
      <span class="theme-toggle__icon" data-theme="light" aria-hidden="true"></span>
      <span class="theme-toggle__text">Dark mode</span>
    </button>
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
          <path d="M3.72 20.78c-.7.28-1.38-.38-1.19-1.09l2.03-7.48L2.53 4.7c-.19-.71.51-1.34 1.2-1.06l18 7.36c.92.38.92 1.7 0 2.08l-18 7.36ZM6.2 13.18l-.86 3.2 10.02-4.61L5.34 7.16l.86 3.2h8.52c.37 0 .62.39.46.72-.42.88-1.32 2.1-2.6 2.1H6.2Z"/>
        </svg>
      </button>
    </form>
  </div>
</div>

<script>
const root = document.documentElement;
const log = document.getElementById('log');
const form = document.getElementById('f');
const q = document.getElementById('q');
const themeToggle = document.getElementById('themeToggle');
const themeToggleText = themeToggle.querySelector('.theme-toggle__text');
const themeToggleIcon = themeToggle.querySelector('.theme-toggle__icon');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
const storedTheme = localStorage.getItem('ai-chat-theme');

function setTheme(theme){
  const normalized = theme === 'dark' ? 'dark' : 'light';
  root.setAttribute('data-theme', normalized);
  themeToggle.setAttribute('aria-pressed', normalized === 'dark');
  themeToggleText.textContent = normalized === 'dark' ? 'Light mode' : 'Dark mode';
  themeToggleIcon.dataset.theme = normalized;
}

if(storedTheme){
  setTheme(storedTheme);
}else{
  setTheme(prefersDark.matches ? 'dark' : 'light');
}

themeToggle.addEventListener('click', ()=>{
  const current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  const next = current === 'dark' ? 'light' : 'dark';
  setTheme(next);
  localStorage.setItem('ai-chat-theme', next);
});

prefersDark.addEventListener('change', (event)=>{
  if(!localStorage.getItem('ai-chat-theme')){
    setTheme(event.matches ? 'dark' : 'light');
  }
});

function formatResponse(text){
  const fragment = document.createDocumentFragment();
  const cleaned = (text || '').trim();
  if(!cleaned){
    fragment.append(document.createTextNode('(No response)'));
    return fragment;
  }

  const sections = cleaned
    .replace(/\r\n/g, '\n')
    .split(/\n{2,}/)
    .map(section => section
      .split('\n')
      .map(line => line.replace(/^\s*[-*•]\s*/, '').trim())
      .filter(Boolean)
      .join(' '))
    .map(section => section.replace(/\s+/g, ' ').trim())
    .filter(Boolean);

  if(!sections.length){
    const paragraph = document.createElement('p');
    paragraph.textContent = cleaned.replace(/\s+/g, ' ');
    fragment.append(paragraph);
    return fragment;
  }

  if(sections.length > 1){
    const headingText = (()=>{
      const first = sections.shift();
      const sentenceMatch = first.match(/[^.!?]+[.!?]?/);
      if(sentenceMatch && sentenceMatch[0].length < first.length){
        sections.unshift(first.slice(sentenceMatch[0].length).trim());
        return sentenceMatch[0].trim();
      }
      return first;
    })();
    const heading = document.createElement('h3');
    heading.textContent = headingText;
    fragment.append(heading);
  }

  sections.forEach(section => {
    if(!section) return;
    const paragraph = document.createElement('p');
    paragraph.textContent = section;
    fragment.append(paragraph);
  });

  if(fragment.children.length === 0){
    const fallback = document.createElement('p');
    fallback.textContent = cleaned;
    fragment.append(fallback);
  }

  return fragment;
}

function add(role, text, {loading=false} = {}){
  const meta = document.createElement('div');
  meta.className = 'meta ' + (role === 'user' ? 'meta-user' : 'meta-bot');
  meta.textContent = role === 'user' ? 'You' : 'Sombokchab AI';
  const div = document.createElement('div');
  div.className = 'bubble ' + (role === 'user' ? 'user' : 'bot');
  if(role !== 'user' && !loading){
    div.textContent = '';
    div.classList.add('bot-formatted');
    div.append(formatResponse(text));
  }else{
    div.textContent = text;
  }
  log.append(meta, div);
  requestAnimationFrame(()=>div.classList.add('show'));
  div.scrollIntoView({behavior:'smooth', block:'end'});
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
    loadingBubble.classList.add('bot-formatted');
    loadingBubble.append(formatResponse(data.reply || '(No response)'));
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
