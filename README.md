# PHP 쇼핑몰 프로젝트

## 개발환경
<p>
   <a href="https://developer.apple.com/documentation/macos-release-notes/macos-13-release-notes"><img src="https://img.shields.io/badge/macOS-13.0.1-%23000000?logo=macOs" alt="macOS"></a>
   <a href=""><img src="https://img.shields.io/badge/Visual%20Studio-1.75.1-%235C2D91?logo=Visual%20Studio" alt="Visual%20Studio"></a>
   <a href="https://brew.sh/index_ko"><img src="https://img.shields.io/badge/Homebrew-3.6.7-%23FBB040?logo=homebrew" alt="Homebrew"></a>
   <a href="https://git-scm.com"><img src="https://img.shields.io/badge/GitHub-2.38.1-%23181717?logo=GitHub" alt="GitHub"></a>
   <a href=""><img src="https://img.shields.io/badge/Apache-2.4-%23D22128?logo=Apache" alt="Apache"></a>
   <a href="https://httpd.apache.org/docs/2.4/ko/"><img src="https://img.shields.io/badge/PHP-8.1.12-%23777BB4?logo=PHP" alt="PHP"></a>
   <a href="https://www.php.net/"><img src="https://img.shields.io/badge/MySQL-8.0.31-%234479A1?logo=MySQL" alt="MySQL"></a>
   <a href="https://docs.phpmyadmin.net/en/latest/"><img src="https://img.shields.io/badge/phpMyAdmin-5.2.0-%236C78AF" alt="phpMyAdmin"></a>
   <a href=""><img src="https://img.shields.io/badge/Bootstrap-3.3.2-%237952B3?logo=Bootstrap" alt="Bootstrap"></a>
   <a href="https://jquery.com/"><img src="https://img.shields.io/badge/jQuery-3.6.0-%230769AD?logo=jQuery" alt="jQuery"></a>
   <a href=""><img src="https://img.shields.io/badge/JavaScript-%23F7DF1E?logo=JavaScript" alt="JavaScript"></a>
   <a href=""><img src="https://img.shields.io/badge/CSS3-%231572B6?logo=CSS3" alt="CSS3"></a>
   <a href=""><img src="https://img.shields.io/badge/HTML5-%23E34F26?logo=HTML5" alt="HTML5"></a>
   <a href=""><img src="https://img.shields.io/badge/JPEG-%238A8A8A?logo=JPEG" alt="JPEG"></a>
   <a href=""><img src="https://img.shields.io/badge/AdminLte-%23000000" alt="AdminLte"></a>
   <a href=""><img src="https://img.shields.io/badge/Dropzone-%23000000" alt="Dropzone"></a>
   <a href=""><img src="https://img.shields.io/badge/DataTables-%23000000" alt="DataTables"></a>
   <a href=""><img src="https://img.shields.io/badge/FloraEditor-%23000000" alt="FloraEditor"></a>
   
</p>

## 기능 목록
### 1. 순서 갱신형 게시판 기능(sequences Order Board)
#### 지원 기능
- 계층형 형태의 게시판
   - 게시글 목록
      - 페이징 기능
   - 게시글 작성
      - 위즈윅 에디터(WYSIWYG Eidtor) 플로라 에디터(Flora Editor)를 활용
      > https://froala.com/wysiwyg-editor/
   - 게시글 보기
   - 게시글 삭제
   - 게시글 수정
   - 게시글 답글

#### 특이사항
- 계층형 게시글 구조 공부를 위해서 최초 설계를 댓글(Comment)에서 답글 형태로 변경
- 페이지 디자인은 부트 스트랩(Bootstrap) 활용
> http://bootstrapk.com/
### 2. 회원 관리 기능(Membership)
#### 지원 기능
- 회원가입 기능
- 회원 로그인 기능
- 마이페이지 기능
   - 회원 정보 수정
   - 나의 게시글 목록
      - 게시글 목록 템플릿 활용
 #### 특이사항
- 회원가입 및 로그인 페이지 템플릿은 무료 템플릿 활용
   > https://inpa.tistory.com/entry/CSS-%F0%9F%92%8D-%EB%A1%9C%EA%B7%B8%EC%9D%B8-%ED%9A%8C%EC%9B%90%EA%B0%80%EC%9E%85-%ED%8E%98%EC%9D%B4%EC%A7%80-%EC%8A%A4%ED%83%80%EC%9D%BC-%F0%9F%96%8C%EF%B8%8F-%EB%AA%A8%EC%9D%8C

### 3. 관리자 페이지(Admin)
#### 지원 기능
- 게시글 관리
   - 게시글 보기
   - 게시글 수정
   - 게시글 삭제
- 회원 관리
   - 회원 정보 보기
   - 회원 정보 수정
   - 회원 탈퇴
#### 특이사항
- 없음