<?php
/** Practical 13: AJAX/Fetch CRUD page (no page reload) - consumes api/students_api.php and api/events_api.php */
require_once __DIR__ . '/layout.php';
require_login(['admin']);
page_header('AJAX CRUD', 'Practical 13', 'AJAX / Fetch CRUD (Practical 13)');
?>
<div class="card" style="margin-bottom:1.5rem;">
  <span class="badge badge-primary">Practical 13 · Admin only</span>
  <h1>AJAX CRUD without Page Reload</h1>
  <p style="margin-bottom:0;">List, add, update and delete records through the PHP JSON API using the Fetch API, with debounced search and loading / success / error states.</p>
</div>
<div class="filter-pills" role="tablist" aria-label="Resource">
  <button class="filter-pill active" id="tabStudents" type="button">🎓 Students API</button>
  <button class="filter-pill" id="tabEvents" type="button">🎟️ Events API</button>
</div>
<div class="toolbar-container">
  <div class="search-input-wrapper"><span class="search-icon">🔍</span><input type="search" id="ajaxSearch" class="search-input" placeholder="Search (debounced 350 ms)..." aria-label="Search"></div>
  <button class="btn btn-accent btn-sm" id="addBtn" type="button">➕ Add Record</button>
  <span id="statusPill" class="badge badge-primary">Idle</span>
</div>
<div class="card" style="padding:0;margin-top:1rem;"><div class="table-responsive"><table class="data-table"><thead id="ajaxHead"></thead><tbody id="ajaxBody"><tr><td style="padding:2rem;text-align:center;">Loading…</td></tr></tbody></table></div></div>
<div class="pagination-container" id="ajaxPager"></div>

<div class="modal-backdrop" id="recordModal" role="dialog" aria-modal="true" aria-labelledby="recordModalTitle">
  <div class="modal-dialog">
    <div class="modal-header"><h3 class="modal-title" id="recordModalTitle">Record</h3><button class="modal-close-btn" type="button" aria-label="Close">✕</button></div>
    <form id="recordForm" novalidate>
      <div class="modal-body" id="recordFields"></div>
      <div class="modal-footer"><button type="button" class="btn btn-outline" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>
<?php
$js = <<<'JS'
<script>
const CSRF = document.querySelector('meta[name="csrf"]')?.content || '__CSRF__';
const RES = {
  students: { url: 'api/students_api.php', cols: [['enrollment_no','Enrollment'],['full_name','Name'],['email','Email'],['course','Course'],['year','Yr'],['cgpa','CGPA']],
    fields: [['enrollment_no','Enrollment No','text'],['full_name','Full name','text'],['email','Email','email'],['mobile','Mobile','text'],['course','Course','select',['B.Tech Computer Engineering','B.Tech Information Technology','B.Tech Electronics & Communication']],['year','Year','select',['1','2','3','4']],['gender','Gender','select',['Male','Female','Other']],['cgpa','CGPA','text']] },
  events: { url: 'api/events_api.php', cols: [['title','Title'],['category','Category'],['event_date','Date'],['venue','Venue'],['seats','Seats'],['status','Status']],
    fields: [['title','Title','text'],['category','Category','select',['Hackathon','Workshop','Seminar','Competition','Cultural','Sports','Career','Business','Networking']],['event_date','Date','date'],['venue','Venue','text'],['seats','Seats','text'],['status','Status','select',['open','closed']]] }
};
let cur = 'students', page = 1, q = '', editing = null;
const $ = id => document.getElementById(id);
const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
function setStatus(txt, cls){ const p=$('statusPill'); p.textContent=txt; p.className='badge '+cls; }

async function api(method, params, body){
  setStatus('Loading…','badge-warning');
  const opt = { method, headers: { 'Accept':'application/json' }, credentials: 'same-origin' };
  let url = RES[cur].url;
  if (method === 'GET') url += '?' + new URLSearchParams(params);
  else { opt.headers['Content-Type']='application/json'; opt.headers['X-CSRF-Token']=CSRF; opt.body=JSON.stringify(body); }
  const res = await fetch(url, opt);
  const json = await res.json().catch(() => ({status:'error', message:'Invalid JSON response'}));
  json.http = res.status;
  if (!res.ok) { setStatus('Error '+res.status,'badge-danger'); }
  else setStatus('OK '+res.status,'badge-success');
  return json;
}
async function load(){
  $('ajaxBody').innerHTML = '<tr><td colspan="9" style="padding:2rem;text-align:center;">⏳ Loading data…</td></tr>';
  try {
    const r = await api('GET', { q, page, per: 8 });
    if (r.status !== 'success') throw new Error(r.message);
    const cols = RES[cur].cols;
    $('ajaxHead').innerHTML = '<tr>' + cols.map(c => `<th>${c[1]}</th>`).join('') + '<th>Actions</th></tr>';
    $('ajaxBody').innerHTML = r.data.length ? r.data.map(row => '<tr>' + cols.map(c => `<td>${esc(row[c[0]])}</td>`).join('') +
      `<td style="white-space:nowrap;"><button class="btn btn-outline btn-sm" data-edit="${row.id}">Edit</button> <button class="btn btn-danger btn-sm" data-del="${row.id}">Delete</button></td></tr>`).join('')
      : '<tr><td colspan="9" style="padding:2rem;text-align:center;">No records found.</td></tr>';
    const m = r.meta, from = m.total ? (m.page-1)*m.per+1 : 0, to = Math.min(m.total, m.page*m.per);
    renderPagination($('ajaxPager'), m.pages, m.page, p => { page = p; load(); }, m.total, from, to);
  } catch (e) {
    $('ajaxBody').innerHTML = `<tr><td colspan="9" style="padding:2rem;text-align:center;color:var(--danger);">⚠️ ${esc(e.message)}</td></tr>`;
    showToast('Load failed', e.message, 'error');
  }
}
function openForm(rec){
  editing = rec; $('recordModalTitle').textContent = rec ? 'Edit record' : 'Add record';
  $('recordFields').innerHTML = RES[cur].fields.map(([k,label,type,opts]) => {
    const v = rec ? rec[k] : '';
    const inp = type === 'select' ? `<select class="form-control" name="${k}" id="f_${k}">${opts.map(o=>`<option ${String(v)===o?'selected':''}>${esc(o)}</option>`).join('')}</select>`
      : `<input class="form-control" type="${type}" name="${k}" id="f_${k}" value="${esc(v)}">`;
    return `<div class="form-group"><label class="form-label" for="f_${k}">${label}</label>${inp}<div class="invalid-feedback" id="err_${k}"></div></div>`;
  }).join('');
  openModal('recordModal');
}
async function save(e){
  e.preventDefault();
  const body = Object.fromEntries(new FormData($('recordForm')).entries());
  body.action = editing ? 'update' : 'create'; if (editing) body.id = editing.id;
  const r = await api('POST', null, body);
  document.querySelectorAll('.invalid-feedback').forEach(x => x.style.display='none');
  if (r.status === 'success') { closeModal('recordModal'); showToast('Success', r.message, 'success'); load(); }
  else { if (r.errors) Object.entries(r.errors).forEach(([k,m]) => { const el=$('err_'+k); if(el){ el.textContent=m; el.style.display='block'; } }); showToast('Error '+r.http, r.message, 'error'); }
}
$('recordForm').addEventListener('submit', save);
document.querySelectorAll('#recordModal [data-close]').forEach(b => b.addEventListener('click', () => closeModal('recordModal')));
$('addBtn').addEventListener('click', () => openForm(null));
$('ajaxSearch').addEventListener('input', debounce(e => { q = e.target.value.trim(); page = 1; load(); }, 350));
$('ajaxBody').addEventListener('click', async e => {
  const ed = e.target.closest('[data-edit]'), dl = e.target.closest('[data-del]');
  if (ed) { const r = await api('GET', { id: ed.dataset.edit }); if (r.status === 'success') openForm(r.data); else showToast('Error', r.message, 'error'); }
  if (dl && confirm('Delete this record?')) { const r = await api('POST', null, { action: 'delete', id: dl.dataset.del }); showToast(r.status === 'success' ? 'Deleted' : 'Error', r.message, r.status === 'success' ? 'success' : 'error'); load(); }
});
function switchTab(t){ cur = t; page = 1; q = ''; $('ajaxSearch').value=''; $('tabStudents').classList.toggle('active', t==='students'); $('tabEvents').classList.toggle('active', t==='events'); load(); }
$('tabStudents').onclick = () => switchTab('students'); $('tabEvents').onclick = () => switchTab('events');
load();
</script>
JS;
page_footer(str_replace('__CSRF__', e(csrf_token()), $js));
