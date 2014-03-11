# GIT ALIASES
alias gb='git branch'
alias gba='git branch -a'
alias gc='git commit -v'
alias gl='git pull'
alias glm='git pull origin master'
alias gp='git push'
alias gpm='git push origin master'
alias gst='git status'
alias gd='git diff | $GIT_EDITOR -'
alias gmv='git mv'
alias gdc='git svn dcommit'
alias git_count_commits='git shortlog -s -n --all'

# MORE ALIASES
alias ..="cd .."
alias la="ls -lah"
alias pa="ps aux"

#ALIAS PESSOAIS
alias rmdotfiles='find . -name "._*" -print0 | xargs -0 rm -rfv'
alias rmdsstore='find . -name ".DS_Store" -print0 | xargs -0 rm -rfv'
alias rmdot='rmdotfiles && rmdsstore'

parse_git_branch() {
	git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/(\1)/'
}

# 30m - Black
# 31m - Red
# 32m - Green
# 33m - Yellow
# 34m - Blue
# 35m - Purple
# 36m - Cyan
# 37m - White
# Everything else is green...
# 0 - Normal
# 1 - Bold
# 2 - 
function prompt {
       local BLACK="\[\033[0;30m\]"
       local RED="\[\033[0;31m\]"
       local GREEN="\[\033[0;32m\]"
       local YELLOW="\[\033[0;33m\]"
       local BLUE="\[\033[0;34m\]"
       local PURPLE="\[\033[0;35m\]"
       local CYAN="\[\033[0;36m\]"
       local WHITE="\[\033[0;37m\]"
       local WHITEBOLD="\[\033[1;37m\]"
export PS1="${WHITE}\u${RED}@${PURPLE}\h ${CYAN}\w ${WHITE}\$(parse_git_branch) ${YELLOW}$ \[\e[m\]\[\e[m\]"
}
prompt

# WELCOME MESSAGE
echo -e ""
echo -ne "Today is "; date
echo -e ""; cal;
echo -ne "Up time: ";uptime | awk /'up/ {print $3,$4}'
echo "";

# If not running interactively, don't do anything
[ -z "$PS1" ] && return

# Make bash check its window size after a process completes
shopt -s checkwinsize

# LS COLORS
export CLICOLOR=1
export LSCOLORS=ExFxCxDxBxegedabagacad
