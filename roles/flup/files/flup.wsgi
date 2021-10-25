activate_this = '/opt/flup/venv/bin/activate_this.py'
with open(activate_this) as f:
    exec(f.read(), {'__file__': activate_this})

import sys
sys.path.insert(0, '/opt/flup/')

from flup import app

# Debug
app.debug = True
application = app
