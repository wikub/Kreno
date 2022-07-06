FROM gitpod/workspace-full:2022-06-20-19-54-55

RUN sudo update-alternatives --set php $(which php7.4)