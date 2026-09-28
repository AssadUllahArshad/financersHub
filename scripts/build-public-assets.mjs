import { readFile, writeFile, mkdir } from 'node:fs/promises';
import { createHash } from 'node:crypto';
import { transform } from 'esbuild';

const groups = {
  publication: ['site.css', 'editorial.css', 'info.css', 'integration.css'],
  studio: ['site.css', 'editorial.css', 'info.css', 'admin.css', 'integration.css', 'studio-ui.css'],
  login: ['site.css', 'login.css'],
};
await mkdir('public/assets/generated', { recursive: true });
const manifest = {};
for (const [name, files] of Object.entries(groups)) {
  const source = (await Promise.all(files.map(file => readFile(`public/assets/${file}`, 'utf8')))).join('\n');
  const { code } = await transform(source, { loader: 'css', minify: true, target: 'es2020' });
  const filename = `${name}.${createHash('sha256').update(code).digest('hex').slice(0, 12)}.css`;
  await writeFile(`public/assets/generated/${filename}`, code);
  manifest[name] = filename;
  console.log(`${name}: ${source.length} -> ${code.length} bytes; one stylesheet`);
}
await writeFile('public/assets/generated/manifest.json', JSON.stringify(manifest, null, 2));
