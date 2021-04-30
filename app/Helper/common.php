<?php

function uplodeFile($file, $foldername)
{
    $category_iconExt = $file->getClientOriginalName();
    $category_icon = pathinfo($category_iconExt, PATHINFO_FILENAME);
    $category_icon_extension = $file->getClientOriginalExtension();
    $category_icon_fileNameToStore = $category_icon . '_' . time() . '.' . $category_icon_extension;
    $path = $file->storeAs('public/' . $foldername, $category_icon_fileNameToStore);
    return  str_replace('public/','',$path);
}

function asset_storage($path, $secure = null)
{
    return app('url')->asset('storage/app/' . $path, $secure);
}