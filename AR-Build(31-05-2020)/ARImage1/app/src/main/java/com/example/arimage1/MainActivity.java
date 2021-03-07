package com.example.arimage1;

import android.content.Intent;
import android.media.AudioManager;
import android.media.MediaPlayer;
import android.media.ToneGenerator;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.text.Layout;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;
import com.google.ar.core.AugmentedImage;
import com.google.ar.core.Frame;
import com.google.ar.sceneform.AnchorNode;
import com.google.ar.sceneform.FrameTime;
import com.google.ar.sceneform.Scene;
import com.google.ar.sceneform.math.Vector3;
import com.google.ar.sceneform.rendering.Color;
import com.google.ar.sceneform.rendering.ExternalTexture;
import com.google.ar.sceneform.rendering.ModelRenderable;

import java.io.IOException;
import java.util.Collection;


@RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
public class MainActivity extends AppCompatActivity {
    private ExternalTexture texture;
    private MediaPlayer mediaPlayer;
    private CustomARFragment arFragment;
    private Scene scene;
    private boolean isImageInFrame = false;
    private AugmentedImage activeAugmentedImage;
    private ModelRenderable renderable;
    private Button pause,stopScan,status,rewind,forward;
    private AnchorNode anchorNode;
    private boolean toScan = true;//to if toScan=false then user does not want to scan
    private Thread updateListenerThread;
    private int activeAugmentedImageIndex = -1;//-1 means no image detected
    private Scene.OnUpdateListener onUpdateListener;
    private ToneGenerator toneGen1;
    private int activeVideoBufferedPercent;
    private int skipFrames;
    private int skipFramesValue = 0;
    private String sessionId;
    private LinearLayout panelControl;
    void dismissArVideo() {
        if (activeAugmentedImageIndex != -1) {
            activeAugmentedImage = null;
            activeAugmentedImageIndex = -1;
            activeVideoBufferedPercent=0;
            anchorNode.setRenderable(null);
            anchorNode.getAnchor().detach();
            arVideoSetStatus(false);
        }
        mediaPlayer.reset();
        System.gc();
    }

    void pauseArVideo() {
        anchorNode.setRenderable(null);
        if (mediaPlayer.isPlaying())
            mediaPlayer.pause();

    }

    /* status=true video detected
        status=false v
     */
    void arVideoSetStatus(boolean status) {
        toneGen1.startTone(ToneGenerator.TONE_CDMA_PIP, 100);
        if (status) {
            isImageInFrame = true;
            this.status.setText(activeVideoBufferedPercent+"% Buffered");
            //this.pause.setVisibility(View.VISIBLE);
            this.panelControl.setVisibility(View.VISIBLE);
        } else {
            isImageInFrame = false;
            this.status.setText("Not Detected");
            //this.pause.setVisibility(View.INVISIBLE);
            this.panelControl.setVisibility(View.INVISIBLE);
        }

    }

    void resumeArVideo() {
        if (!mediaPlayer.isPlaying() && pause.getText().equals("Pause"))
            mediaPlayer.start();
        if (anchorNode.getRenderable() == null)
            anchorNode.setRenderable(renderable);

    }


    @RequiresApi(api = Build.VERSION_CODES.N)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        skipFrames = skipFramesValue;
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        Intent intent=getIntent();
        sessionId =intent.getStringExtra("Id");
        pause = findViewById(R.id.pause);
        stopScan = findViewById(R.id.stopScan);
        status = findViewById(R.id.status);
        arFragment = (CustomARFragment) getSupportFragmentManager().findFragmentById(R.id.arfragment);
        forward=findViewById(R.id.forward);
        rewind=findViewById(R.id.rewind);
        panelControl=findViewById(R.id.mediaControlPanel);
        texture = new ExternalTexture();
        mediaPlayer = new MediaPlayer();
        mediaPlayer.setSurface(texture.getSurface());
        anchorNode = new AnchorNode();
        toneGen1 = new ToneGenerator(AudioManager.STREAM_MUSIC, 100);
        ModelRenderable
                .builder()
                .setSource(this, Uri.parse("video_screen.sfb"))
                .build()
                .thenAccept(modelRenderable -> {
                    modelRenderable.getMaterial().setExternalTexture("videoTexture", texture);
                    modelRenderable.getMaterial().setFloat4("keyColor", new Color(0.01843f, 1f, 0.098f));
                    renderable = modelRenderable;
                    renderable.setShadowCaster(false);
                    renderable.setShadowReceiver(false);
                });

        rewind.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int current=mediaPlayer.getCurrentPosition();
                if(current>10000) {
                    mediaPlayer.seekTo(current-10000);
                }
                else {
                    mediaPlayer.seekTo(0);
                }
            }
        });

        forward.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                    mediaPlayer.seekTo(mediaPlayer.getCurrentPosition()+10000);
            }
        });

        //Video will be played after detection on link
        mediaPlayer.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
            @Override
            public void onPrepared(MediaPlayer mp) {
                mediaPlayer.start();//video started
            }
        });
        mediaPlayer.setOnBufferingUpdateListener(new MediaPlayer.OnBufferingUpdateListener() {
            @Override
            public void onBufferingUpdate(MediaPlayer mp, int percent) {
                activeVideoBufferedPercent=percent;
                status.setText(percent+"% Buffered");
            }
        });

        //Listener List
        pause.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mediaPlayer.isPlaying()) {
                    mediaPlayer.pause();
                    pause.setText("Resume");
                } else {
                    mediaPlayer.start();
                    pause.setText("Pause");
                }
            }
        });
        stopScan.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (toScan) {
                    dismissArVideo();
                    stopScan.setText("Start Scan");
                    toScan = false;
                    scene.removeOnUpdateListener(onUpdateListener);
                } else {
                    stopScan.setText("Stop Scan");
                    scene.addOnUpdateListener(onUpdateListener);
                    toScan = true;
                }

            }
        });
        scene = arFragment.getArSceneView().getScene();
        anchorNode.setParent(scene);
        onUpdateListener = frameTime -> onUpdate(frameTime);
        scene.addOnUpdateListener(onUpdateListener);
    }

    @RequiresApi(api = Build.VERSION_CODES.N)
    private void onUpdate(FrameTime frameTime) {
        if (!toScan) {
            return;
        }

       /* if (skipFrames == 0)//check only 1 frame out of flagValue frames
        {
            skipFrames = skipFramesValue;
        } else {
            skipFrames--;
            return;
        }*/
        Frame frame = arFragment.getArSceneView().getArFrame();
        /*
        Trackables have the state of all images which are in the database
        Update Trackables have the status of only change of status of any image
        if image is found the camera frame then it changes the TrackingMethod to fullTracking
        * */
        Collection<AugmentedImage> augmentedImages = frame.getUpdatedTrackables(AugmentedImage.class);

        if (augmentedImages.isEmpty()) {
            return;
        }

        /*Below logic checks weather image is being tracked or not(means in the area of camera)
        TrackingMethod says that image is in the camera frame or not
       */

        if (activeAugmentedImageIndex != -1 && isImageInFrame) {
            for (AugmentedImage image : augmentedImages) {
                if (image.getTrackingMethod() != AugmentedImage.TrackingMethod.FULL_TRACKING && image.getIndex() == activeAugmentedImageIndex) {
                    arVideoSetStatus(false);
                    pauseArVideo();
                    //Log.i("Non tracking:","fdvdv");
                    return;
                }
            }
        }
        /*
            if already detected image is in frame but it is paused
            then play it again
         */
        if (activeAugmentedImageIndex != -1 && (!isImageInFrame)) {
            for (AugmentedImage image : augmentedImages) {
                if (image.getTrackingMethod() == AugmentedImage.TrackingMethod.FULL_TRACKING && image.getIndex() == activeAugmentedImageIndex) {
                    arVideoSetStatus(true);

                    //resume logic
                    resumeArVideo();
                    //Log.i("Tracking:","resume video");
                    return;
                }
            }

        }
        if (!isImageInFrame) {
            for (AugmentedImage image : augmentedImages) {
                if (image.getTrackingMethod() == AugmentedImage.TrackingMethod.FULL_TRACKING) {
                    //if (image.getIndex()>-1) {
                    arVideoSetStatus(true);
                    this.dismissArVideo();
                    activeAugmentedImage = image;
                    activeAugmentedImageIndex = image.getIndex();
                    playVideo(image.getExtentX(), image.getExtentZ());
                    break;
                    //}


                }

            }
        }



        /*
        Below code is for efficiency and it is helping in non crash
        exprience
         */
        augmentedImages = null;
        frame = null;
        System.gc();
        return;
    }

    @RequiresApi(api = Build.VERSION_CODES.N)
    private void playVideo(float extentX, float extentZ) {
        this.pause.setText("Pause");
        mediaPlayer.setLooping(true);
        try {
           // mediaPlayer.setDataSource(getApplicationContext().getAssets().openFd(id+"/vid" + activeAugmentedImageIndex + ".mp4"));
           mediaPlayer.setDataSource("http://192.168.0.108/portal/"+ sessionId +"/vid"+activeAugmentedImageIndex+".mp4");
            mediaPlayer.prepareAsync();
        } catch (IOException e) {
            //file open error here
        }
        texture.getSurfaceTexture().setOnFrameAvailableListener(
                surfaceTexture -> {
                    anchorNode.setRenderable(renderable);
                    texture.getSurfaceTexture().setOnFrameAvailableListener(null);
                });
        anchorNode.setWorldScale(new Vector3(extentX, 1f, extentZ));
        anchorNode.setAnchor(activeAugmentedImage.createAnchor(activeAugmentedImage.getCenterPose()));
        //mediaplayer will play by prepared listener
        //if mediaPlayer.prepare then use mediaPlayer.play()
    }

}