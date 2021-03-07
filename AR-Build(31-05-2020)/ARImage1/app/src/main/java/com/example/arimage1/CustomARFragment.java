package com.example.arimage1;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import androidx.annotation.Nullable;
import com.google.ar.core.AugmentedImageDatabase;
import com.google.ar.core.Config;
import com.google.ar.core.Session;
import com.google.ar.sceneform.rendering.PlaneRenderer;
import com.google.ar.sceneform.ux.ArFragment;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;

public class CustomARFragment extends ArFragment {
    private static final String TAG = "MyActivity";
    @Override
    protected Config getSessionConfiguration(Session session)
    {
        Config config=new Config(session);
        config.setLightEstimationMode(Config.LightEstimationMode.DISABLED);
        config.setFocusMode(Config.FocusMode.AUTO);
        config.setUpdateMode(Config.UpdateMode.LATEST_CAMERA_IMAGE);
        AugmentedImageDatabase aid= null;
        try {
            String baseDir = Environment.getExternalStorageDirectory().getAbsolutePath();
            Log.i("6003",baseDir);
            String fileName = "ArDatabase/img.imgdb";
            File f = new File(baseDir + File.separator + fileName);
            FileInputStream stream = new FileInputStream(f);
            //InputStream stream= getContext().getAssets().open("path.imgdb");
            aid=AugmentedImageDatabase.deserialize(session,stream);
        } catch (IOException e) {
            Log.i("File open error","564");
            e.printStackTrace();
        }

        /*AugmentedImageDatabase aid=new AugmentedImageDatabase(session);
     Bitmap image =BitmapFactory.decodeResource(getResources(),R.drawable.img0);
     int index=aid.addImage("img1",image);
     */

        config.setAugmentedImageDatabase(aid);
        this.getArSceneView().setupSession(session);
        return config;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        FrameLayout frameLayout= (FrameLayout) super.onCreateView(inflater, container, savedInstanceState);
        getPlaneDiscoveryController().hide();//hides white dots
        getPlaneDiscoveryController().setInstructionView(null);//hides instruction loop
        getArSceneView().getPlaneRenderer().setEnabled(false);
        getArSceneView().setLightDirectionUpdateEnabled(false);
        //initializeSession();

        return frameLayout;
    }
}