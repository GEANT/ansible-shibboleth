activate_this = '/opt/flup/venv/bin/activate_this.py'
execfile(activate_this, dict(__file__=activate_this))

import sys
sys.path.insert(0, '/opt/flup/')

from flup import app

# Debug
app.debug = True
application = app
