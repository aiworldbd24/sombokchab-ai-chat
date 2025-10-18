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
      gap:10px;
      position:sticky;
      bottom:0;
      padding-top:8px;
      background:linear-gradient(180deg,rgba(19,23,34,0),rgba(19,23,34,0.92) 70%);
      align-items:stretch;
    }
    input,button,textarea{font:inherit;color:inherit}
    .input-wrap{
      flex:1;
      position:relative;
      display:flex;
      align-items:center;
      padding:10px 18px;
      border-radius:calc(var(--radius) - 4px);
      border:1px solid transparent;
      background:
        linear-gradient(rgba(10,13,19,0.78), rgba(10,13,19,0.78)) padding-box,
        linear-gradient(135deg, rgba(58,192,160,0.6), rgba(74,227,188,0.9), rgba(58,192,160,0.6)) border-box;
      background-size:100% 100%, 220% 220%;
      animation:borderFlow 6s ease-in-out infinite;
      box-shadow:0 12px 36px rgba(8,12,20,0.45);
      transition:box-shadow 0.35s ease, transform 0.35s ease;
      overflow:hidden;
    }
    .input-wrap::after{
      content:"";
      position:absolute;
      inset:-6px;
      border-radius:inherit;
      background:radial-gradient(circle at 20% 20%, rgba(74,227,188,0.18), transparent 60%),
                 radial-gradient(circle at 80% 80%, rgba(58,192,160,0.14), transparent 55%);
      opacity:0.55;
      pointer-events:none;
      animation:borderPulse 4.5s ease-in-out infinite;
    }
    @keyframes borderFlow{
      0%{background-position:0 0, 0% 50%;}
      50%{background-position:0 0, 100% 50%;}
      100%{background-position:0 0, 0% 50%;}
    }
    @keyframes borderPulse{
      0%{opacity:0.35;transform:scale(0.98)}
      50%{opacity:0.75;transform:scale(1.02)}
      100%{opacity:0.35;transform:scale(0.98)}
    }
    .input-wrap:focus-within{
      box-shadow:0 16px 38px rgba(15,25,34,0.65), 0 0 0 2px rgba(74,227,188,0.35);
      transform:translateY(-1px);
    }
    textarea{
      flex:1;
      border:0;
      background:transparent;
      resize:vertical;
      min-height:40px;
      max-height:140px;
      padding:0;
      color:var(--fg);
      line-height:1.45;
      width:100%;
    }
    textarea:focus{outline:none}
    button{
      border:0;
      display:flex;
      align-items:center;
      justify-content:center;
      min-width:52px;
      padding:0 18px;
      border-radius:calc(var(--radius) - 4px);
      background:linear-gradient(135deg,var(--accent),var(--accent-strong));
      color:#041015;
      cursor:pointer;
      box-shadow:0 8px 20px rgba(58,192,160,0.35);
      transition:transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
      height:100%;
    }
    button svg{width:18px;height:18px;fill:currentColor}
    button:hover{transform:translateY(-1px);box-shadow:0 12px 24px rgba(58,192,160,0.45)}
    button:active{transform:translateY(0)}
    .bubble.bot.bot-formatted{
      display:flex;
      flex-direction:column;
      gap:6px;
    }
    .bubble.bot h3{
      margin:0 0 12px;
      font-size:18px;
      font-weight:600;
      color:var(--accent-strong);
      letter-spacing:0.01em;
    }
    .bubble.bot p{
      margin:0 0 12px;
      color:var(--fg);
    }
    .bubble.bot ul{
      margin:0 0 12px 18px;
      padding:0 0 0 12px;
      display:grid;
      gap:6px;
    }
    .bubble.bot li{line-height:1.5}
    .bubble.bot > *:last-child{margin-bottom:0}
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
        <path d="M3.72 20.78c-.7.28-1.38-.38-1.19-1.09l2.03-7.48L2.53 4.7c-.19-.71.51-1.34 1.2-1.06l18 7.36c.92.38.92 1.7 0 2.08l-18 7.36ZM6.2 13.18l-.86 3.2 10.02-4.61L5.34 7.16l.86 3.2h8.52c.37 0 .62.39.46.72-.42.88-1.32 2.1-2.6 2.1H6.2Z"/>
      </svg>
    </button>
  </form>
</div>
</div>

<script>
const log = document.getElementById('log');
const form = document.getElementById('f');
const q = document.getElementById('q');

function formatResponse(text){
  const fragment = document.createDocumentFragment();
  const cleaned = (text || '').trim();
  if(!cleaned){
    fragment.append(document.createTextNode('(No response)'));
    return fragment;
  }

  const lines = cleaned.split(/\r?\n/).map(line => line.trim()).filter(Boolean);
  if(!lines.length){
    fragment.append(document.createTextNode(cleaned));
    return fragment;
  }

  let headingLine = lines.shift();
  let headingCandidate = headingLine.replace(/^#+\s*/, '');
  if(headingCandidate.split(' ').length > 12){
    const headingSentences = headingCandidate.match(/[^.!?]+[.!?]?/g) || [headingCandidate];
    headingCandidate = headingSentences.shift().trim();
    const remainder = headingSentences.join(' ').trim();
    if(remainder){
      lines.unshift(remainder);
    }
  }

  if(!headingCandidate){
    headingCandidate = 'Response';
  }

  const heading = document.createElement('h3');
  heading.textContent = headingCandidate;
  fragment.append(heading);

  const bulletItems = [];
  const paragraphSentences = [];

  lines.forEach(line => {
    if(/^[-*•]/.test(line)){
      bulletItems.push(line.replace(/^[-*•]\s*/, ''));
      return;
    }

    const sentences = (line.match(/[^.!?]+[.!?]?/g) || [line]).map(sentence => sentence.trim()).filter(Boolean);
    if(sentences.length > 1){
      bulletItems.push(sentences.shift());
    }
    paragraphSentences.push(...sentences);
  });

  if(!bulletItems.length && paragraphSentences.length){
    bulletItems.push(paragraphSentences.shift());
  }

  if(!bulletItems.length){
    bulletItems.push(`Key takeaway: ${headingCandidate}`);
  }

  const list = document.createElement('ul');
  bulletItems.forEach(itemText => {
    if(!itemText) return;
    const li = document.createElement('li');
    li.textContent = itemText;
    list.append(li);
  });
  if(list.childElementCount){
    fragment.append(list);
  }

  if(paragraphSentences.length){
    let buffer = [];
    paragraphSentences.forEach(sentence => {
      if(!sentence) return;
      buffer.push(sentence);
      const currentText = buffer.join(' ');
      if(currentText.length >= 140 || buffer.length >= 2){
        const paragraph = document.createElement('p');
        paragraph.textContent = currentText;
        fragment.append(paragraph);
        buffer = [];
      }
    });
    if(buffer.length){
      const paragraph = document.createElement('p');
      paragraph.textContent = buffer.join(' ');
      fragment.append(paragraph);
    }
  }

  return fragment;
}

function add(role, text, {loading=false} = {}){
  const meta = document.createElement('div');
  meta.className = 'meta ' + (role === 'user' ? 'meta-user' : 'meta-bot');
  meta.textContent = role === 'user' ? 'You' : 'Sombokchab AI';
  const div = document.createElement('div'); div.className = 'bubble ' + (role==='user'?'user':'bot');
  if(role === 'assistant' && !loading){
    div.textContent = '';
    div.classList.add('bot-formatted');
    div.append(formatResponse(text));
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
