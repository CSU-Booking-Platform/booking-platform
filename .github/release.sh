#!/usr/bin/env bash

## FUNCTIONS
# usage: `get_ghr`
function get_ghr {
  wget https://github.com/github-release/github-release/releases/download/v0.10.0/linux-amd64-github-release.bz2
  bzip2 -d linux-amd64-github-release.bz2
  chmod +x linux-amd64-github-release
  mv linux-amd64-github-release ghr
}

# usage: `tag_and_push`
function tag_and_push {
  git mkver tag
  git push --tags
}

# usage: `create_release`
function create_release {
  ./ghr release \
    --tag $TAG \
    --security-token $GITHUB_TOKEN \
    --user $GITHUB_OWNER \
    --repo $GITHUB_REPO
}

# usage: `upload_release_file "file-name.ext" path/to/file`
function upload_release_file {
  ./ghr upload \
    --tag $TAG \
    --security-token $GITHUB_TOKEN \
    --user $GITHUB_OWNER \
    --repo $GITHUB_REPO \
    --name $1 \
    --file $2
}

echo "Fetching dependencies..."
get_ghr
echo "Fetching dependencies...OK"

echo "Creating & pushing version tag..."
tag_and_push
echo "Creating & pushing version tag..."

echo "Creating github.com release..."
create_release
echo "Creating github.com release...OK"

echo "Uploading static assets..."
upload_release_file "app.css" public/css/app.css
echo "Uploaded: app.css"
upload_release_file "app.css.map" public/css/app.css.map
echo "Uploaded: app.css.map "
upload_release_file "app.js" public/js/app.js
echo "Uploaded: app.js "
upload_release_file "app.js.map" public/js/app.js.map
echo "Uploaded: app.js.map "
upload_release_file "app.js.LICENSE.txt" public/js/app.js.LICENSE.txt
echo "Uploaded: app.js.LICENSE.txt "
upload_release_file "mix-manifest.json" public/mix-manifest.json
echo "Uploaded: mix-manifest.json "
echo "Uploading static assets...OK"
